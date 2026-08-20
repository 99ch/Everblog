<?php

declare(strict_types=1);


namespace PrestaShop\Module\Everpsblog\Controller\Admin;

use PrestaShop\Module\Everpsblog\Application\Blog\CategoryCommandAssembler;
use PrestaShop\Module\Everpsblog\Core\Domain\Blog\Command\DeleteCategoryCommand;
use PrestaShop\Module\Everpsblog\Core\Domain\Blog\CommandBus\CommandBusInterface;
use PrestaShop\Module\Everpsblog\Form\DataProvider\CategoryFormDataProvider;
use PrestaShop\Module\Everpsblog\Form\Type\Admin\CategoryType;
use PrestaShop\Module\Everpsblog\Grid\Data\CategoryGridDataFactory;
use PrestaShop\Module\Everpsblog\Grid\Definition\CategoryGridDefinitionFactory;
use PrestaShop\Module\Everpsblog\Service\AdminBlogImageManager;
use PrestaShop\Module\Everpsblog\Service\BlogInstallService;
use PrestaShop\Module\Everpsblog\Service\BlogSitemapService;
use PrestaShop\Module\Everpsblog\Service\ContextStateService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

if (!defined('_PS_VERSION_')) {
    exit;
}


class CategoryController extends AbstractDomainController
{
    private $commandBus;
    private $commandAssembler;
    private $definitionFactory;
    private $dataFactory;
    private $formDataProvider;
    private $blogSitemapService;
    private $blogInstallService;
    private $adminBlogImageManager;

    public function __construct(ContextStateService $contextStateService, CommandBusInterface $commandBus, CategoryCommandAssembler $commandAssembler, CategoryGridDefinitionFactory $definitionFactory, CategoryGridDataFactory $dataFactory, CategoryFormDataProvider $formDataProvider, BlogSitemapService $blogSitemapService, BlogInstallService $blogInstallService, AdminBlogImageManager $adminBlogImageManager)
    {
        parent::__construct($contextStateService);
        $this->commandBus = $commandBus;
        $this->commandAssembler = $commandAssembler;
        $this->definitionFactory = $definitionFactory;
        $this->dataFactory = $dataFactory;
        $this->formDataProvider = $formDataProvider;
        $this->blogSitemapService = $blogSitemapService;
        $this->blogInstallService = $blogInstallService;
        $this->adminBlogImageManager = $adminBlogImageManager;
    }

    public function indexAction(Request $request): Response
    {
        return $this->render('@Modules/everpsblog/views/templates/admin/modern/resource.html.twig', [
            'definition' => $this->definitionFactory->build(),
            'data' => $this->dataFactory->build($this->getContextShopId(), $this->getContextLangId(), $request->query->all()),
            'resource' => 'category',
            'currentResource' => 'category',
            'createUrl' => $this->generateUrl('everpsblog_admin_category_form'),
            'bulkActionUrl' => $this->generateUrl('everpsblog_admin_category_bulk'),
            'bulkCsrfTokenId' => 'everpsblog_category_bulk',
            'navigationLinks' => $this->getAdminNavigationLinks(),
        ]);
    }

    public function bulkAction(Request $request): Response
    {
        $this->validateCsrfToken($request, 'everpsblog_category_bulk');

        $action = (string) $request->request->get('bulk_action', '');
        $rawIds = (array) $request->request->get('bulk_ids', []);
        $ids = [];
        foreach ($rawIds as $rawId) {
            $id = (int) $rawId;
            if ($id > 0) {
                $ids[] = $id;
            }
        }
        $ids = array_values(array_unique($ids));

        if (empty($ids)) {
            $this->addFlash('warning', $this->transAdmin('Veuillez sélectionner au moins une catégorie.'));

            return $this->redirectToRoute('everpsblog_admin_category');
        }

        if ('delete' !== $action) {
            $this->addFlash('error', $this->transAdmin('Action groupée inconnue.'));

            return $this->redirectToRoute('everpsblog_admin_category');
        }

        $shopId = $this->getContextShopId();
        $success = 0;
        $protected = [];
        $blocked = [];
        $failures = [];

        foreach ($ids as $categoryId) {
            if ($this->blogInstallService->isProtectedCategoryId($categoryId, $shopId)) {
                $protected[] = $categoryId;
                continue;
            }
            try {
                $this->commandBus->handle(new DeleteCategoryCommand($categoryId));
                $this->deleteBannerImage($categoryId);
                ++$success;
            } catch (\RuntimeException $exception) {
                $blocked[] = $categoryId;
                \PrestaShopLogger::addLog(
                    '[everpsblog][CategoryController::bulkDelete] #' . $categoryId . ' ' . $exception->getMessage(),
                    2
                );
            } catch (\Throwable $exception) {
                $failures[] = $categoryId;
                \PrestaShopLogger::addLog(
                    '[everpsblog][CategoryController::bulkDelete] #' . $categoryId . ' ' . $exception->getMessage(),
                    3
                );
            }
        }

        if ($success > 0) {
            $this->refreshSitemapsAfterBackOfficeChange($this->blogSitemapService, false);
            $this->addFlash('success', $this->transAdmin('%count% catégorie(s) supprimée(s).', ['%count%' => $success]));
        }
        if (!empty($protected)) {
            $this->addFlash('warning', $this->transAdmin('Ignorées (catégories système) : #%ids%.', ['%ids%' => implode(', #', $protected)]));
        }
        if (!empty($blocked)) {
            $this->addFlash('warning', $this->transAdmin('Suppression bloquée pour : #%ids%.', ['%ids%' => implode(', #', $blocked)]));
        }
        if (!empty($failures)) {
            $this->addFlash('error', $this->transAdmin('Échec de la suppression pour : #%ids%.', ['%ids%' => implode(', #', $failures)]));
        }

        return $this->redirectToRoute('everpsblog_admin_category');
    }

    public function formAction(Request $request, ?int $categoryId = null): Response
    {
        $isEdit = null !== $categoryId;
        if ($isEdit && $this->blogInstallService->isRootCategoryId((int) $categoryId, $this->getContextShopId())) {
            $this->addFlash('warning', $this->transAdmin('La catégorie racine est gérée automatiquement et ne peut pas être modifiée depuis le back-office.'));

            return $this->redirectToRoute('everpsblog_admin_category');
        }

        $csrfTokenId = $isEdit ? 'everpsblog_category_update_' . $categoryId : 'everpsblog_category_create';
        $bannerImageHelp = $isEdit ? $this->buildBannerImageHelp((int) $categoryId) : '';
        $hasBannerImage = $isEdit ? $this->hasBannerImage((int) $categoryId) : false;

        $form = $this->createForm(CategoryType::class, $this->formDataProvider->getData($categoryId), [
            'method' => Request::METHOD_POST,
            'action' => $isEdit
                ? $this->generateUrl('everpsblog_admin_category_edit', ['categoryId' => $categoryId])
                : $this->generateUrl('everpsblog_admin_category_form'),
            'banner_image_help' => $bannerImageHelp,
            'has_banner_image' => $hasBannerImage,
            'current_category_id' => $categoryId,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateCsrfToken($request, $csrfTokenId);

            if ($form->isValid()) {
                $data = (array) $form->getData();

                try {
                    $savedCategoryId = $isEdit
                        ? $this->commandBus->handle($this->commandAssembler->assembleUpdate((int) $categoryId, $data))
                        : $this->commandBus->handle($this->commandAssembler->assembleCreate($data));
                    if ((bool) $form->get('delete_banner_image')->getData()) {
                        $this->deleteBannerImage((int) $savedCategoryId);
                    }
                    $this->handleBannerImageUpload($form->get('banner_image_file')->getData(), (int) $savedCategoryId);
                    $this->refreshSitemapsAfterBackOfficeChange($this->blogSitemapService);
                    $submitAction = (string) $request->request->get('_submit_action', 'save');

                    $this->addFlash('success', $isEdit ? $this->transAdmin('Catégorie mise à jour.') : $this->transAdmin('Catégorie créée.'));

                    if ('save_and_stay' === $submitAction) {
                        return $this->redirectToRoute('everpsblog_admin_category_edit', ['categoryId' => $savedCategoryId]);
                    }

                    return $this->redirectToRoute('everpsblog_admin_category');
                } catch (\Throwable $exception) {
                    $message = $this->transAdmin('Impossible d\'enregistrer la catégorie : %error%', ['%error%' => $this->describeException($exception)]);
                    $form->addError(new FormError($message));
                    $this->addFlash('error', $message);
                    \PrestaShopLogger::addLog(
                        '[everpsblog][CategoryController::formAction] ' . $exception->getMessage()
                            . ' @ ' . $exception->getFile() . ':' . $exception->getLine(),
                        3
                    );
                }
            }
        }

        return $this->render('@Modules/everpsblog/views/templates/admin/modern/form.html.twig', [
            'resource' => 'category',
            'entityId' => $categoryId,
            'csrfTokenId' => $csrfTokenId,
            'form' => $form->createView(),
            'currentResource' => 'category',
            'cancelUrl' => $this->generateUrl('everpsblog_admin_category'),
            'createUrl' => $this->generateUrl('everpsblog_admin_category_form'),
            'previewUrl' => $isEdit ? $this->buildPreviewUrlForResource('category', (int) $categoryId) : '',
            'navigationLinks' => $this->getAdminNavigationLinks(),
            'everBlogLanguages' => $this->getEverBlogLanguages(),
            'qcdPageBuilderTargets' => $this->buildQcdPageBuilderTargets('everpsblog_category', $categoryId, [
                'content' => $this->transAdmin('Modifier le contenu avec le Page Builder'),
                'bottom_content' => $this->transAdmin('Modifier le contenu bas de page avec le Page Builder'),
            ]),
        ]);
    }

    public function createAction(Request $request): JsonResponse
    {
        $this->validateCsrfToken($request, 'everpsblog_category_create');

        $categoryId = $this->commandBus->handle($this->commandAssembler->assembleCreate($request->request->all()));
        $sitemapsRefreshed = $this->refreshSitemapsAfterBackOfficeChange($this->blogSitemapService, false);

        return new JsonResponse(['id_ever_category' => $categoryId, 'sitemaps_refreshed' => $sitemapsRefreshed], JsonResponse::HTTP_CREATED);
    }

    public function updateAction(int $categoryId, Request $request): JsonResponse
    {
        $this->validateCsrfToken($request, 'everpsblog_category_update_' . $categoryId);

        $updatedCategoryId = $this->commandBus->handle($this->commandAssembler->assembleUpdate($categoryId, $request->request->all()));
        $sitemapsRefreshed = $this->refreshSitemapsAfterBackOfficeChange($this->blogSitemapService, false);

        return new JsonResponse(['id_ever_category' => $updatedCategoryId, 'sitemaps_refreshed' => $sitemapsRefreshed], JsonResponse::HTTP_OK);
    }

    public function deleteAction(int $categoryId, Request $request): JsonResponse
    {
        $this->validateCsrfToken($request, 'everpsblog_category_delete_' . $categoryId);

        try {
            $this->commandBus->handle(new DeleteCategoryCommand($categoryId));
            $this->deleteBannerImage($categoryId);
            $this->refreshSitemapsAfterBackOfficeChange($this->blogSitemapService, false);
        } catch (\RuntimeException $exception) {
            return new JsonResponse(
                ['error' => $this->transAdmin($exception->getMessage())],
                JsonResponse::HTTP_CONFLICT
            );
        }

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    private function validateCsrfToken(Request $request, string $tokenId): void
    {
        $token = (string) ($request->request->get('_csrf_token') ?: $request->request->get('_token') ?: $request->headers->get('X-CSRF-TOKEN'));

        if (!$this->isCsrfTokenValid($tokenId, $token)) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
    }

    private function handleBannerImageUpload($uploadedImage, int $categoryId): void
    {
        $this->adminBlogImageManager->upload($uploadedImage, $categoryId, $this->getContextShopId(), 'category_banner');
    }

    private function deleteBannerImage(int $categoryId): void
    {
        $this->adminBlogImageManager->delete($categoryId, $this->getContextShopId(), 'category_banner');
    }

    private function hasBannerImage(int $categoryId): bool
    {
        return $this->adminBlogImageManager->hasImage($categoryId, $this->getContextShopId(), 'category_banner');
    }

    private function buildBannerImageHelp(int $categoryId): string
    {
        return $this->adminBlogImageManager->buildImageHelp(
            $categoryId,
            $this->getContextShopId(),
            'category_banner',
            $this->transAdmin('Image bannière actuelle'),
            $this->transAdmin('Voir l\'aperçu')
        );
    }
}

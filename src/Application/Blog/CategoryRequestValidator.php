<?php

declare(strict_types=1);


namespace PrestaShop\Module\Everpsblog\Application\Blog;

if (!defined('_PS_VERSION_')) {
    exit;
}


class CategoryRequestValidator extends AbstractRequestValidator
{
    public function validate(array $requestData, ?int $currentCategoryId = null): array
    {
        $this->resetErrors();

        $this->ensureDefaultTitle($requestData);
        $requestData = $this->normalizeSeoFields($requestData);

        $parentCategoryId = (int) ($requestData['id_parent_category'] ?? 0);
        if ($parentCategoryId > 0 && null !== $currentCategoryId && $parentCategoryId === $currentCategoryId) {
            $this->addFieldError('id_parent_category', $this->transAdmin('Une catégorie ne peut pas être son propre parent.'));
        } elseif ($parentCategoryId > 0 && !$this->existsInCurrentShopModuleTable('ever_blog_category', 'id_ever_category', $parentCategoryId, 'ever_blog_category_shop')) {
            $this->addFieldError('id_parent_category', $this->transAdmin('Catégorie parente introuvable (id : %id%).', ['%id%' => $parentCategoryId]));
        }

        $categoryProducts = $this->normalizeIntCollection($requestData['category_products'] ?? []);
        foreach ($categoryProducts as $productId) {
            if (!$this->existsInCurrentShopPrestashopTable('product', 'id_product', $productId, 'product_shop')) {
                $this->addFieldError('category_products', $this->transAdmin('Produit introuvable (id : %id%).', ['%id%' => $productId]));
            }
        }
        $requestData['category_products'] = $categoryProducts;

        $this->throwIfInvalid();

        return $requestData;
    }
}

<?php

declare(strict_types=1);


namespace PrestaShop\Module\Everpsblog\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

if (!defined('_PS_VERSION_')) {
    exit;
}


final class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_ever_post', ChoiceType::class, [
                'required' => true,
                'label' => 'Article',
                'choices' => $this->getPostChoices(),
                'placeholder' => '— Sélectionner un article —',
            ])
            ->add('nickname', TextType::class, ['required' => false, 'label' => 'Auteur'])
            ->add('content', TextType::class, ['required' => false, 'label' => 'Commentaire'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'Modules.Everpsblog.Admin',
        ]);
    }

    /**
     * @return array<string, int>
     */
    private function getPostChoices(): array
    {
        $idShop = (int) \Context::getContext()->shop->id;
        $idLang = (int) \Context::getContext()->language->id;

        $rows = \Db::getInstance()->executeS(
            'SELECT DISTINCT p.id_ever_post, pl.title
            FROM `' . _DB_PREFIX_ . 'ever_blog_post` p
            LEFT JOIN `' . _DB_PREFIX_ . 'ever_blog_post_lang` pl
                ON (pl.id_ever_post = p.id_ever_post AND pl.id_lang = ' . $idLang . ')
            INNER JOIN `' . _DB_PREFIX_ . 'ever_blog_post_shop` ps
                ON (ps.id_ever_post = p.id_ever_post AND ps.id_shop = ' . $idShop . ')
            WHERE p.post_status = "published"
            ORDER BY p.date_add DESC, p.id_ever_post DESC'
        ) ?: [];

        $choices = [];
        foreach ($rows as $row) {
            $id = (int) $row['id_ever_post'];
            $label = trim((string) ($row['title'] ?? ''));
            $choices[$label ?: sprintf('Post #%d', $id)] = $id;
        }

        return $choices;
    }
}

<?php

declare(strict_types=1);

namespace PrestaShop\Module\Everpsblog\Form\Type\Admin;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

class ConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('theme', ChoiceType::class, [
                'label' => 'Thème front-office',
                'choices' => (array) ($options['theme_choices'] ?? []),
                'help' => 'Sélectionne le jeu de templates utilisé pour les pages et blocs du blog.',
            ])
            ->add('route', TextType::class, [
                'label' => 'Route du blog',
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 64]),
                ],
            ])
            ->add('allow_comments', CheckboxType::class, [
                'label' => 'Autoriser les commentaires',
                'required' => false,
            ])
            ->add('check_comments', CheckboxType::class, [
                'label' => 'Modérer les commentaires',
                'required' => false,
            ])
            ->add('show_ai_summary_banner', CheckboxType::class, [
                'label' => 'Afficher le bloc de résumé IA sur les pages d\'articles',
                'required' => false,
            ])
            ->add('rss_enabled', CheckboxType::class, [
                'label' => 'Activer les flux RSS',
                'required' => false,
                'help' => 'Ajoute des liens de flux RSS sur les pages blog, catégorie, tag et auteur.',
            ])
            ->add('posts_per_page', IntegerType::class, [
                'label' => 'Articles par page',
                'constraints' => [new GreaterThan(['value' => 0])],
            ])
            ->add('home_posts', IntegerType::class, [
                'label' => 'Articles en page d\'accueil',
                'constraints' => [new GreaterThan(['value' => 0])],
            ])
            ->add('product_posts', IntegerType::class, [
                'label' => 'Articles en page produit',
                'constraints' => [new GreaterThan(['value' => 0])],
            ])
            ->add('excerpt_length', IntegerType::class, [
                'label' => 'Longueur de l\'extrait',
                'constraints' => [new GreaterThan(['value' => 0])],
            ])
            ->add('title_length', IntegerType::class, [
                'label' => 'Longueur du titre',
                'constraints' => [new GreaterThan(['value' => 0])],
            ])
            ->add('empty_trash_days', IntegerType::class, [
                'label' => 'Vider la corbeille après (jours)',
                'help' => 'Les articles en corbeille depuis plus longtemps sont supprimés automatiquement.',
                'constraints' => [new GreaterThanOrEqual(['value' => 0])],
            ])
            ->add('default_author_id', ChoiceType::class, [
                'label' => 'Auteur par défaut pour les articles sans auteur',
                'required' => false,
                'placeholder' => 'Aucun auteur par défaut',
                'choices' => (array) ($options['author_choices'] ?? []),
            ])
            ->add('header_bg_color', TextType::class, [
                'label' => 'Couleur d\'en-tête du blog',
                'required' => false,
                'help' => 'Couleur de fond principale appliquée aux héros et bannières du blog.',
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #0a0f54.',
                    ]),
                ],
            ])
            ->add('header_bg_alt_color', TextType::class, [
                'label' => 'Couleur secondaire d\'en-tête du blog',
                'required' => false,
                'help' => 'Couleur de fond secondaire pour les thèmes avec dégradé en héro.',
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #b64a32.',
                    ]),
                ],
            ])
            ->add('header_overlay_bg_color', TextType::class, [
                'label' => 'Couleur de l\'overlay héro du blog',
                'required' => false,
                'help' => 'Couleur d\'overlay appliquée au-dessus des images ou dégradés héro.',
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #121212.',
                    ]),
                ],
            ])
            ->add('page_bg_color', TextType::class, [
                'label' => 'Fond de page du blog',
                'required' => false,
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #ffffff.',
                    ]),
                ],
            ])
            ->add('surface_bg_color', TextType::class, [
                'label' => 'Fond des surfaces du blog',
                'required' => false,
                'help' => 'Fond pour les surfaces de contenu et les contrôles de formulaire.',
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #ffffff.',
                    ]),
                ],
            ])
            ->add('card_bg_color', TextType::class, [
                'label' => 'Fond des cartes du blog',
                'required' => false,
                'help' => 'Fond pour les cartes d\'articles et blocs compacts.',
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #f1f1f1.',
                    ]),
                ],
            ])
            ->add('soft_bg_color', TextType::class, [
                'label' => 'Fond des sections secondaires du blog',
                'required' => false,
                'help' => 'Fond pour les sections secondaires comme les articles liés.',
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #ededed.',
                    ]),
                ],
            ])
            ->add('placeholder_bg_color', TextType::class, [
                'label' => 'Fond de substitution du blog',
                'required' => false,
                'help' => 'Fond affiché lorsqu\'une image est absente.',
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #d8d8d8.',
                    ]),
                ],
            ])
            ->add('accent_bg_color', TextType::class, [
                'label' => 'Fond d\'accentuation du blog',
                'required' => false,
                'help' => 'Fond d\'accentuation pour les blocs mis en avant et les badges.',
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #8cced2.',
                    ]),
                ],
            ])
            ->add('header_title_color', TextType::class, [
                'label' => 'Couleur du titre d\'en-tête du blog',
                'required' => false,
                'help' => 'Couleur appliquée aux titres des en-têtes blog, article, catégorie, tag, auteur et recherche.',
                'attr' => [
                    'type' => 'color',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^#[0-9a-fA-F]{6}$/',
                        'message' => 'La couleur doit être au format hexadécimal, par exemple #ffffff.',
                    ]),
                ],
            ])
            ->add('wordpress_api_url', TextType::class, [
                'label' => 'URL du site WordPress',
                'required' => false,
                'help' => 'Exemple : https://example.com ou https://example.com/wp-json/wp/v2',
                'constraints' => [
                    new Length(['max' => 255]),
                    new Url(),
                ],
            ])
            ->add('wordpress_api_user', TextType::class, [
                'label' => 'Nom d\'utilisateur REST WordPress',
                'required' => false,
                'help' => 'Facultatif pour l\'import d\'articles publics. Obligatoire pour le contenu non public.',
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ])
            ->add('wordpress_api_password', PasswordType::class, [
                'label' => 'Mot de passe d\'application WordPress',
                'required' => false,
                'always_empty' => false,
                'help' => 'Utilisez un mot de passe d\'application WordPress, pas le mot de passe principal.',
                'constraints' => [
                    new Length(['max' => 255]),
                ],
            ])
            ->add('wordpress_import_post_status', ChoiceType::class, [
                'label' => 'Statut des articles importés',
                'choices' => [
                    'Publié' => 'published',
                    'Brouillon' => 'draft',
                ],
            ])
            ->add('wordpress_enable_authors', CheckboxType::class, [
                'label' => 'Activer les auteurs importés',
                'required' => false,
            ])
            ->add('wordpress_enable_categories', CheckboxType::class, [
                'label' => 'Activer les catégories importées',
                'required' => false,
            ])
            ->add('wordpress_enable_tags', CheckboxType::class, [
                'label' => 'Activer les tags importés',
                'required' => false,
            ]);

        foreach (\Language::getLanguages(false) as $lang) {
            $idLang = (int) $lang['id_lang'];
            $isoCode = strtoupper((string) ($lang['iso_code'] ?? ''));
            $suffix = $isoCode ? sprintf(' (%s)', $isoCode) : sprintf(' (langue #%d)', $idLang);

            $builder
                ->add(sprintf('main_title_%d', $idLang), TextType::class, [
                    'label' => 'Titre principal du blog' . $suffix,
                    'required' => false,
                    'constraints' => [
                        new Length(['max' => 255]),
                    ],
                ])
                ->add(sprintf('hero_subtitle_%d', $idLang), TextType::class, [
                    'label' => 'Sous-titre héro du blog' . $suffix,
                    'required' => false,
                    'constraints' => [
                        new Length(['max' => 255]),
                    ],
                ])
                ->add(sprintf('top_text_%d', $idLang), TextareaType::class, [
                    'label' => 'Texte au-dessus du blog' . $suffix,
                    'required' => false,
                    'attr' => [
                        'data-ever-richtext' => '1',
                        'rows' => 8,
                    ],
                ])
                ->add(sprintf('bottom_text_%d', $idLang), TextareaType::class, [
                    'label' => 'Texte en-dessous du blog' . $suffix,
                    'required' => false,
                    'attr' => [
                        'data-ever-richtext' => '1',
                        'rows' => 8,
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_token_id' => 'everpsblog_configuration',
            'translation_domain' => 'Modules.Everpsblog.Admin',
            'author_choices' => [],
            'theme_choices' => [],
        ]);
    }
}

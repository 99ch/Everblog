<?php

declare(strict_types=1);


namespace PrestaShop\Module\Everpsblog\Grid\Definition;

use PrestaShop\Module\Everpsblog\Core\Grid\GridDefinition;

if (!defined('_PS_VERSION_')) {
    exit;
}


final class CategoryGridDefinitionFactory
{
    public function build(): GridDefinition
    {
        return new GridDefinition(
            'category',
            'Catégories',
            [
                ['id' => 'id_ever_category', 'name' => 'ID'],
                ['id' => 'title', 'name' => 'Titre'],
                ['id' => 'active', 'name' => 'Actif'],
            ],
            [
                'q' => 'Rechercher',
                'title' => 'Titre',
            ],
            [
                ['id' => 'delete', 'name' => 'Supprimer la sélection'],
            ]
        );
    }
}

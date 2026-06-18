<?php

declare(strict_types=1);


namespace PrestaShop\Module\Everpsblog\Grid\Definition;

use PrestaShop\Module\Everpsblog\Core\Grid\GridDefinition;

if (!defined('_PS_VERSION_')) {
    exit;
}


final class TagGridDefinitionFactory
{
    public function build(): GridDefinition
    {
        return new GridDefinition(
            'tag',
            'Tags',
            [
                ['id' => 'id_ever_tag', 'name' => '#', 'type' => 'id'],
                ['id' => 'title', 'name' => 'Titre', 'type' => 'text'],
                ['id' => 'link_rewrite', 'name' => 'Slug', 'type' => 'text'],
                ['id' => 'count', 'name' => 'Vues', 'type' => 'number'],
                ['id' => 'active', 'name' => 'Actif', 'type' => 'status'],
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

<?php

declare(strict_types=1);


namespace PrestaShop\Module\Everpsblog\Grid\Definition;

use PrestaShop\Module\Everpsblog\Core\Grid\GridDefinition;

if (!defined('_PS_VERSION_')) {
    exit;
}


final class PostGridDefinitionFactory
{
    public function build(): GridDefinition
    {
        return new GridDefinition(
            'post',
            'Articles',
            [
                ['id' => 'id_ever_post', 'name' => '#', 'type' => 'id', 'sortable' => true],
                ['id' => 'featured_image', 'name' => 'Image', 'type' => 'image'],
                ['id' => 'title', 'name' => 'Titre', 'type' => 'text', 'sortable' => true],
                ['id' => 'post_status', 'name' => 'Statut', 'type' => 'status', 'sortable' => true],
                ['id' => 'date_add', 'name' => 'Date de publication', 'type' => 'date'],
                ['id' => 'count', 'name' => 'Vues', 'type' => 'number', 'sortable' => true],
            ],
            [
                'q' => 'Rechercher',
                'title' => 'Titre',
                'post_status' => 'Statut',
            ],
            [
                ['id' => 'delete', 'name' => 'Supprimer la sélection'],
                ['id' => 'publishall', 'name' => 'Publier la sélection'],
                ['id' => 'duplicate', 'name' => 'Dupliquer la sélection'],
            ]
        );
    }
}

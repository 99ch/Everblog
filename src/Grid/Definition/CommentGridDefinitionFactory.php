<?php

declare(strict_types=1);


namespace PrestaShop\Module\Everpsblog\Grid\Definition;

use PrestaShop\Module\Everpsblog\Core\Grid\GridDefinition;

if (!defined('_PS_VERSION_')) {
    exit;
}


final class CommentGridDefinitionFactory
{
    public function build(): GridDefinition
    {
        return new GridDefinition(
            'comment',
            'Commentaires',
            [
                ['id' => 'id_ever_comment', 'name' => 'ID'],
                ['id' => 'id_ever_post', 'name' => 'ID article'],
                ['id' => 'active', 'name' => 'Actif'],
            ],
            [
                'q' => 'Rechercher',
                'id_ever_post' => 'ID article',
            ],
            [
                ['id' => 'delete', 'name' => 'Supprimer la sélection'],
                ['id' => 'approveall', 'name' => 'Approuver la sélection'],
            ]
        );
    }
}

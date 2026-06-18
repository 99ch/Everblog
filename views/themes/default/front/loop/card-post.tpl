{*
 * card-post.tpl — Option B : liste verticale style éditorial
 * Image à gauche · Titre + extrait à droite · Séparateur entre articles
 *
 * Variables attendues (extraites par post_array.tpl) :
 *   $post_id, $post_title, $post_link, $post_summary, $item, $show_featured_post, $animated
 *}
<article class="everpsblog-post-item" id="everpsblog-{$post_id|escape:'htmlall':'UTF-8'}">
    <div class="row no-gutters align-items-center">

        {* ── Colonne image ──────────────────────────── *}
        {if isset($show_featured_post) && $show_featured_post && isset($item.featured_thumb) && $item.featured_thumb}
        <div class="col-12 col-md-4 everpsblog-post-item__img-col">
            <a href="{$post_link|escape:'htmlall':'UTF-8'}"
               class="everpsblog-post-item__img-wrap d-block"
               title="{$post_title|escape:'htmlall':'UTF-8'}">
                <img
                    src="{$item.featured_thumb|escape:'htmlall':'UTF-8'}"
                    alt="{$post_title|escape:'htmlall':'UTF-8'}"
                    class="everpsblog-post-item__img{if isset($animated) && $animated} animated{/if}"
                    loading="lazy">
            </a>
        </div>
        <div class="col-12 col-md-8 everpsblog-post-item__content everpsblog-post-item__content--with-img">
        {else}
        <div class="col-12 everpsblog-post-item__content">
        {/if}

            {* Date *}
            {if isset($item.date_add) && $item.date_add}
            <span class="everpsblog-post-item__date">
                <time datetime="{$item.date_add|escape:'htmlall':'UTF-8'}">
                    {$item.date_add|date_format:'%d/%m/%Y'|escape:'htmlall':'UTF-8'}
                </time>
            </span>
            {/if}

            {* Titre *}
            <h2 class="everpsblog-post-item__title">
                <a href="{$post_link|escape:'htmlall':'UTF-8'}"
                   class="everpsblog-post-item__title-link">
                    {$post_title|escape:'htmlall':'UTF-8'}
                </a>
            </h2>

            {* Extrait *}
            {if $post_summary}
            <p class="everpsblog-post-item__excerpt">
                {$post_summary|strip_tags|truncate:180:'...'|escape:'html':'UTF-8'}
            </p>
            {/if}

            {* Lien *}
            <a href="{$post_link|escape:'htmlall':'UTF-8'}"
               class="everpsblog-post-item__readmore"
               aria-label="{l s='Read more' d='Modules.Everpsblog.Shop'} {$post_title|escape:'htmlall':'UTF-8'}">
                {l s='Read more' d='Modules.Everpsblog.Shop'} →
            </a>

        </div>
    </div>
</article>

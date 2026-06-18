{*
 * header.tpl — Blog section header component
 *
 * Displays the blog title and category pills.
 * Background is customizable via the CSS variable --everpsblog-header-bg
 * (set it in your theme CSS or via inline style on .everpsblog-header).
 *
 * Variables used (from parent context):
 *   $blog_page_title     — blog title (string, optional)
 *   $evercategory        — array of categories
 *   $pagination          — pagination object
 *}
<section class="everpsblog-header" aria-labelledby="everpsblog-header-title">
    <div class="everpsblog-header__inner">
        <h1 class="everpsblog-header__title" id="everpsblog-header-title">
            {if isset($blog_page_title) && $blog_page_title}
                {$blog_page_title|escape:'htmlall':'UTF-8'}
            {else}
                {l s='Our blog' d='Modules.Everpsblog.Shop'}
            {/if}
        </h1>

        {if !isset($pagination) || $pagination.current_page <= 1}
        {if isset($evercategory) && $evercategory|count > 0}
        <nav class="everpsblog-header__cats" aria-label="{l s='Blog categories' d='Modules.Everpsblog.Shop'}">
            {foreach from=$evercategory item=cat}
                {if !$cat.is_root_category && $cat.link_rewrite != 'home' && $cat.title|lower != 'home'}
                <a class="everpsblog-cat-pill"
                   href="{$link->getModuleLink('everpsblog', 'category', ['id_ever_category' => $cat.id_ever_category, 'link_rewrite' => $cat.link_rewrite])|escape:'htmlall':'UTF-8'}"
                   title="{$cat.title|escape:'htmlall':'UTF-8'}">
                    {$cat.title|escape:'htmlall':'UTF-8'}
                </a>
                {/if}
            {/foreach}
        </nav>
        {/if}
        {/if}
    </div>
</section>

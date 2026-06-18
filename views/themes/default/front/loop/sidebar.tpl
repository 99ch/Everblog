{*
 * sidebar.tpl — Blog sidebar component (Bootstrap 4)
 *
 * Includes: search form, category list.
 * Include this from blog.tpl when a sidebar layout is needed.
 *
 * Variables used (from parent context):
 *   $evercategory  — array of categories
 *   $allow_feed    — bool
 *   $feed_url      — string
 *}
<aside class="everpsblog-sidebar" aria-label="{l s='Blog sidebar' d='Modules.Everpsblog.Shop'}">

    {{* Search *}}
    <div class="everpsblog-sidebar__block">
        <h3 class="everpsblog-sidebar__title">{l s='Search' d='Modules.Everpsblog.Shop'}</h3>
        <form method="get"
              action="{$link->getModuleLink('everpsblog','search')|escape:'htmlall':'UTF-8'}"
              class="everpsblog-search-form"
              data-doofinder-ignore="true">
            <div class="input-group">
                <input id="everpsblog-sidebar-search"
                       class="form-control"
                       type="search"
                       name="keyword"
                       placeholder="{l s='Search by keywords' d='Modules.Everpsblog.Shop'}"
                       aria-label="{l s='Search the blog' d='Modules.Everpsblog.Shop'}"
                       data-doofinder-ignore="true">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        {l s='Search' d='Modules.Everpsblog.Shop'}
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{* Categories *}}
    {if isset($evercategory) && $evercategory|count > 0}
    <div class="everpsblog-sidebar__block">
        <h3 class="everpsblog-sidebar__title">{l s='Categories' d='Modules.Everpsblog.Shop'}</h3>
        <ul class="everpsblog-sidebar__catlist">
            {foreach from=$evercategory item=cat}
                {if !$cat.is_root_category && $cat.link_rewrite != 'home' && $cat.title|lower != 'home'}
                <li>
                    <a href="{$link->getModuleLink('everpsblog', 'category', ['id_ever_category' => $cat.id_ever_category, 'link_rewrite' => $cat.link_rewrite])|escape:'htmlall':'UTF-8'}">
                        {$cat.title|escape:'htmlall':'UTF-8'}
                        {if isset($cat.count) && $cat.count > 0}
                        <span class="everpsblog-sidebar__catcount">{$cat.count|intval}</span>
                        {/if}
                    </a>
                </li>
                {/if}
            {/foreach}
        </ul>
    </div>
    {/if}

    {{* RSS feed *}}
    {if isset($allow_feed) && $allow_feed && isset($feed_url) && $feed_url}
    <div class="everpsblog-sidebar__block">
        <a class="everpsblog-rss-link"
           href="{$feed_url|escape:'htmlall':'UTF-8'}"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="{l s='RSS feed' d='Modules.Everpsblog.Shop'}">
            RSS
        </a>
    </div>
    {/if}

</aside>

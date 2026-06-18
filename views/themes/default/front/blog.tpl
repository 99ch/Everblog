{*
 * 2019-2025 Team Ever
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 *  @author    Team Ever <https://www.team-ever.com/>
 *  @copyright 2019-2025 Team Ever
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{extends file='page.tpl'}

{block name='head' append}
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary">
    {* <meta name="twitter:site" content="@publisher_handle"> *}
    <meta name="twitter:title" content="{$page.meta.title|escape:'htmlall':'UTF-8'} {if isset($pagination) && $pagination.current_page > 0}{l s='(page' d='Modules.Everpsblog.Shop'} {$pagination.current_page|escape:'htmlall':'UTF-8'}/{$pagination.pages_count|escape:'htmlall':'UTF-8'}{l s=')' d='Modules.Everpsblog.Shop'}{/if}">
    <meta name="twitter:description" content="{$page.meta.description|escape:'htmlall':'UTF-8'}">
    {* <meta name="twitter:creator" content="@author_handle"> *}
    <meta name="twitter:image" content="{$shop.logo|escape:'htmlall':'UTF-8'}">
    <!-- Open Graph Card data -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{$urls.current_url|escape:'htmlall':'UTF-8'}">
    <meta property="og:title" content="{$page.meta.title|escape:'htmlall':'UTF-8'} {if isset($pagination) && $pagination.current_page > 0}{l s='(page' d='Modules.Everpsblog.Shop'} {$pagination.current_page|escape:'htmlall':'UTF-8'}/{$pagination.pages_count|escape:'htmlall':'UTF-8'}{l s=')' d='Modules.Everpsblog.Shop'}{/if}">
    <meta property="og:site_name" content="{$shop.name|escape:'htmlall':'UTF-8'}">
    <meta property="og:description" content="{$page.meta.description|escape:'htmlall':'UTF-8'}">
    <meta property="og:image" content="{$shop.logo|escape:'htmlall':'UTF-8'}">
    {if isset($hreflang_links) && $hreflang_links}
        {foreach from=$hreflang_links item=hreflang_link}
            <link rel="alternate" hreflang="{$hreflang_link.hreflang|escape:'htmlall':'UTF-8'}" href="{$hreflang_link.href|escape:'htmlall':'UTF-8'}">
        {/foreach}
        {if isset($hreflang_x_default) && $hreflang_x_default}
            <link rel="alternate" hreflang="x-default" href="{$hreflang_x_default|escape:'htmlall':'UTF-8'}">
        {/if}
    {/if}
    {if isset($allow_feed) && $allow_feed}
    <link rel="alternate" type="application/rss+xml" title="{$page.meta.title|escape:'htmlall':'UTF-8'} {if isset($pagination) && $pagination.current_page > 0}{l s='(page' d='Modules.Everpsblog.Shop'} {$pagination.current_page|escape:'htmlall':'UTF-8'}/{$pagination.pages_count|escape:'htmlall':'UTF-8'}{l s=')' d='Modules.Everpsblog.Shop'}{/if}" href="{$feed_url|escape:'htmlall':'UTF-8'}" />
    {/if}
    <script type="application/ld+json">
    {
    "@context": "https://schema.org",
    "@type": "{$blog_type|escape:'htmlall':'UTF-8'}",
    "mainEntityOfPage": {
      "@type": "WebPage",
      "@id": "https://google.com/article"
    },
    "headline": "{$page.meta.title|escape:'htmlall':'UTF-8'}",
    "image": [
      "{$shop.logo|escape:'htmlall':'UTF-8'}"
     ],
    "datePublished": "{$smarty.now|date_format:'%Y-%m-%d'|escape:'htmlall':'UTF-8'}",
    "dateModified": "{$smarty.now|date_format:'%Y-%m-%d'|escape:'htmlall':'UTF-8'}",
    "author": {
      "@type": "Person",
      "name": "{$shop.name|escape:'htmlall':'UTF-8'}"
    },
     "publisher": {
      "@type": "Organization",
      "name": "{$shop.name|escape:'htmlall':'UTF-8'}",
      "logo": {
        "@type": "ImageObject",
        "url": "{$shop.logo|escape:'htmlall':'UTF-8'}"
      }
    }
    }
    </script>
{/block}

{block name="page_content"}

{* ── Blog header ─────────────────────────────────────────── *}
{include file="{$everpsblog_theme_front_template_base}/loop/header.tpl"}

{* ── Main content ────────────────────────────────────────── *}
<div class="container">

    {* Search bar *}
    <div class="everpsblog-search-toolbar">
        <form method="get"
              action="{$link->getModuleLink('everpsblog','search')|escape:'htmlall':'UTF-8'}"
              class="everpsblog-search-form"
              data-doofinder-ignore="true">
            <div class="input-group">
                <input id="everpsblog-search-input"
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
        {if isset($allow_feed) && $allow_feed && isset($feed_url) && $feed_url}
        <a class="everpsblog-rss-link"
           href="{$feed_url|escape:'htmlall':'UTF-8'}"
           target="_blank"
           rel="noopener noreferrer">
            RSS
        </a>
        {/if}
    </div>

    {if isset($facet_url)}
    <script>var facetUrl = '{$facet_url|escape:'javascript'}';</script>
    {/if}

    {* Pagination label (hidden, used by JS) *}
    <span class="paginated sr-only d-none">
        {if isset($pagination) && $pagination.current_page > 0}
            {l s='(page' d='Modules.Everpsblog.Shop'} {$pagination.current_page|escape:'htmlall':'UTF-8'}/{$pagination.pages_count|escape:'htmlall':'UTF-8'}{l s=')' d='Modules.Everpsblog.Shop'}
        {/if}
    </span>

    {* Optional top editorial text *}
    {if isset($paginated) && !$paginated && isset($default_blog_top_text) && $default_blog_top_text}
    <div class="row mt-2">
        {$default_blog_top_text nofilter}
    </div>
    {/if}

    {* Posts grid *}
    {if isset($post_number) && $post_number > 0}
    <div class="row" id="everpsblog-posts" data-empty-text="{l s='No posts match your filters yet.' d='Modules.Everpsblog.Shop'}">
        {hook h="displayBeforeEverLoop"}
        {foreach from=$posts item=item}
            {include file="{$everpsblog_theme_front_template_base}/loop/post_array.tpl"}
        {/foreach}
    </div>
    {else}
    <div class="alert alert-info">
        {l s='Sorry, there is no post, please come back later !' d='Modules.Everpsblog.Shop'}
    </div>
    {/if}

    {* Pagination *}
    {if isset($post_number) && $post_number > 0 && isset($pagination.should_be_displayed) && $pagination.should_be_displayed}
    <div class="row mt-2">
        {include file='_partials/pagination.tpl' pagination=$pagination}
    </div>
    {/if}

    {hook h="displayAfterEverLoop"}

    {* Optional bottom editorial text *}
    {if isset($paginated) && !$paginated && isset($default_blog_bottom_text) && $default_blog_bottom_text}
    <div class="row mt-2">
        {$default_blog_bottom_text nofilter}
    </div>
    {/if}

    {* Related products *}
    {if isset($everhome_products) && $everhome_products}
    <section id="everpsblog-products" class="mt-4">
        <h2 class="text-center mb-3">{l s='Our best products' d='Modules.Everpsblog.Shop'}</h2>
        <div class="products row">
            {foreach from=$everhome_products item="product"}
                {include file="catalog/_partials/miniatures/product.tpl" product=$product productClasses="col-12"}
            {/foreach}
        </div>
    </section>
    {/if}

</div>
{/block}

{*
 * card-post.tpl — Post card component (Bootstrap 4)
 *
 * Variables expected from post_array.tpl:
 *   $post_id      — integer
 *   $post_title   — string, escaped
 *   $post_link    — URL string
 *   $post_summary — plain text (already decoded by PostViewModel)
 *   $item         — full post array (date_add, featured_thumb, …)
 *   $show_featured_post — bool
 *   $animated     — bool
 *}
<article class="col-12 mb-4" id="everpsblog-{$post_id|escape:'htmlall':'UTF-8'}">
    <div class="card everpsblog-listing-card">
        <div class="row no-gutters align-items-stretch">

            {if isset($show_featured_post) && $show_featured_post && isset($item.featured_thumb) && $item.featured_thumb}
            <div class="col-12 col-md-5 everpsblog-card-img-wrap">
                <a href="{$post_link|escape:'htmlall':'UTF-8'}"
                   class="everpsblog-card-img-link"
                   title="{$post_title|escape:'htmlall':'UTF-8'}">
                    <img
                        src="{$item.featured_thumb|escape:'htmlall':'UTF-8'}"
                        alt="{$post_title|escape:'htmlall':'UTF-8'}"
                        class="everpsblog-card-img{if isset($animated) && $animated} animated flipSideBySide{/if}"
                        loading="lazy"
                        width="400"
                        height="225">
                </a>
            </div>
            <div class="col-12 col-md-7">
            {else}
            <div class="col-12">
            {/if}

                <div class="everpsblog-card-body">
                    <h2 class="everpsblog-card-title">
                        <a href="{$post_link|escape:'htmlall':'UTF-8'}"
                           class="everpsblog-card-title-link">
                            {$post_title|escape:'htmlall':'UTF-8'}
                        </a>
                    </h2>

                    {if isset($item.date_add) && $item.date_add}
                    <p class="everpsblog-card-date">
                        <time datetime="{$item.date_add|escape:'htmlall':'UTF-8'}">
                            {$item.date_add|date_format:'%d/%m/%Y'|escape:'htmlall':'UTF-8'}
                        </time>
                    </p>
                    {/if}

                    {if $post_summary}
                    <p class="everpsblog-card-excerpt">
                        {$post_summary|strip_tags|truncate:220:'...'|escape:'htmlall':'UTF-8'}
                    </p>
                    {/if}

                    <div class="everpsblog-card-footer">
                        <a href="{$post_link|escape:'htmlall':'UTF-8'}"
                           class="btn btn-primary everpsblog-card-btn"
                           title="{$post_title|escape:'htmlall':'UTF-8'}">
                            {l s='Read more' d='Modules.Everpsblog.Shop'}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</article>

{*
 * post_array.tpl — Post loop item wrapper
 *
 * Extracts display variables from $item and delegates
 * rendering to card-post.tpl.
 *}

{* — Post ID — *}
{if isset($item.id) && $item.id}
    {assign var='post_id' value=$item.id}
{elseif isset($item.id_ever_post) && $item.id_ever_post}
    {assign var='post_id' value=$item.id_ever_post}
{else}
    {assign var='post_id' value=0}
{/if}

{* — Title — *}
{if isset($item.title) && $item.title}
    {assign var='post_title' value=$item.title}
{elseif isset($item.meta_title) && $item.meta_title}
    {assign var='post_title' value=$item.meta_title}
{else}
    {assign var='post_title' value=''}
{/if}

{* — Slug — *}
{if isset($item.link_rewrite) && $item.link_rewrite}
    {assign var='post_rewrite' value=$item.link_rewrite}
{else}
    {assign var='post_rewrite' value=''}
{/if}

{* — Summary (plain text, already decoded by PostViewModel) — *}
{if isset($item.summary) && $item.summary}
    {assign var='post_summary' value=$item.summary}
{elseif isset($item.excerpt) && $item.excerpt}
    {assign var='post_summary' value=$item.excerpt}
{elseif isset($item.meta_description) && $item.meta_description}
    {assign var='post_summary' value=$item.meta_description}
{else}
    {assign var='post_summary' value=''}
{/if}

{* — URL — *}
{if isset($item.url) && $item.url}
    {assign var='post_link' value=$item.url}
{elseif isset($item.link) && $item.link}
    {assign var='post_link' value=$item.link}
{else}
    {assign var='post_link' value=$link->getModuleLink('everpsblog', 'post', ['id_ever_post' => $post_id, 'link_rewrite' => $post_rewrite])}
{/if}

{include file="{$everpsblog_theme_front_template_base}/loop/card-post.tpl"}

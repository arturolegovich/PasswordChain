<?xml version="1.0" encoding="UTF-8" ?>
<pwlist version="1.0">
{section name=entry loop=$entries}
    <pwentry>
{if $entries[entry].entryid}        <uuid>{$entries[entry].entryid}</uuid>
{/if}
{if $entries[entry].group}        <group>{$entries[entry].group|escape:"htmlall"}</group>
{/if}
{if $entries[entry].title}        <title>{$entries[entry].title|escape:"htmlall"}</title>
{/if}
{if $entries[entry].login}        <username>{$entries[entry].login|escape:"htmlall"}</username>
{/if}
{if $entries[entry].url}        <url>{strip}
        {$entries[entry].url|escape:"url"}
        {if $entries[entry].params}?
            {foreach name=params from=$entries[entry].params key=name item=value}
                {$name|escape:"htmlall"}={$value|escape:"htmlall"}{if !$smarty.foreach.params.last}&amp;{/if}
            {/foreach}
        {/if}
        {/strip}</url>
{/if}
{if $entries[entry].password}        <password>{$entries[entry].password|escape:"htmlall"}</password>
{/if}
{if $entries[entry].info}        <notes>{$entries[entry].info|escape:"htmlall"}</notes>
{/if}
{if $entries[entry].icon}        <image>{$entries[entry].icon|escape:"htmlall"}</image>
{/if}
    </pwentry>
{/section}
</pwlist>

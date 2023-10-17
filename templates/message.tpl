{if $message}<div class="{$errorclass}">
{if $foradmin}
<div class="adminmessage">{$message}</div>
{else}
<small>{$message}</small>
{/if}
</div>
{/if}
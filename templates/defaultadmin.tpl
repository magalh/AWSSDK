<h3>{$mod->Lang('getstarted')}:</h3>

{if isset($message)}{$message}{/if}

{form_start}
<div class="pageoverflow">
 <p class="pagetext">{$mod->Lang('access_key')}:&nbsp;{cms_help key2='help_access_key' title=$mod->Lang('access_key')}</p>
 <p class="pageinput">
    <input type="text" name="{$actionid}settings_input[access_key]" value="{$mod->GetOptionValue("access_key")}" size="50" maxlength="50"/>
 </p>
</div>
<div class="pageoverflow">
 <p class="pagetext">{$mod->Lang('access_secret_key')}:</p>
 <p class="pageinput">
    <input type="text" name="{$actionid}settings_input[access_secret_key]" value="{$mod->GetOptionValue("access_secret_key")}" size="50" maxlength="50"/>
 </p>
</div>
<div class="pageoverflow">
 <p class="pageinput">
        <input type="submit" name="{$actionid}submit" value="{$mod->Lang('admin_save')}"/>
 </p>
</div>
{form_end}
{if $mod->is_developer_mode()}
  {*get_template_vars*}
{/if}

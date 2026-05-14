{form_start}
{assign var='auth_method' value=$mod->GetPreference('auth_method', 'credentials')}

<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('auth_method')}:</p>
  <p class="pageinput">
    <select name="{$actionid}auth_method" id="awssdk_auth_method">
      <option value="credentials" {if $auth_method == 'credentials'}selected{/if}>{$mod->Lang('auth_credentials')}</option>
      <option value="profile" {if $auth_method == 'profile'}selected{/if}>{$mod->Lang('auth_profile')}</option>
      <option value="iam_role" {if $auth_method == 'iam_role'}selected{/if}>{$mod->Lang('auth_iam_role')}</option>
    </select>
    <br/>
    <small>{$mod->Lang('help_auth_method')}</small>
  </p>
</div>

<fieldset id="awssdk_credentials_fields">
  <legend>{$mod->Lang('auth_credentials')}</legend>
  <div class="pageoverflow">
    <p class="pagetext">{$mod->Lang('access_key')}:</p>
    <p class="pageinput">
      <input type="text" name="{$actionid}access_key" value="{$mod->GetPreference('access_key')}" size="50" />
    </p>
  </div>
  <div class="pageoverflow">
    <p class="pagetext">{$mod->Lang('access_secret_key')}:</p>
    <p class="pageinput">
      <input type="password" name="{$actionid}access_secret_key" value="{$mod->GetPreference('access_secret_key')}" size="50" />
    </p>
  </div>
</fieldset>

<fieldset id="awssdk_profile_fields">
  <legend>{$mod->Lang('auth_profile')}</legend>
  <div class="pageoverflow">
    <p class="pagetext">{$mod->Lang('aws_profile_name')}:</p>
    <p class="pageinput">
      <input type="text" name="{$actionid}aws_profile" value="{$mod->GetPreference('aws_profile', 'default')}" size="30" />
      <br/>
      <small>{$mod->Lang('help_aws_profile')}</small>
    </p>
  </div>
  <div class="pageoverflow">
    <p class="pagetext">{$mod->Lang('credentials_file')}:</p>
    <p class="pageinput">
      <input type="text" name="{$actionid}credentials_file" value="{$mod->GetPreference('credentials_file')}" size="80" />
      <br/>
      <small>{$mod->Lang('help_credentials_file')}</small>
    </p>
  </div>
  {if $detected_credentials}
  <div class="pageoverflow" style="background:#f0f8ff;padding:10px;border-left:4px solid #1e88e5;">
    <p class="pagetext">{$mod->Lang('detected_credentials')}:</p>
    <p class="pageinput">
      {if isset($detected_credentials.error)}
        <span style="color:red;">{$detected_credentials.error}</span>
      {else}
        <strong>{$mod->Lang('profile')}:</strong> {$detected_credentials.source}<br/>
        <strong>{$mod->Lang('access_key')}:</strong> {$detected_credentials.access_key}
      {/if}
    </p>
  </div>
  {/if}
</fieldset>

<fieldset id="awssdk_iam_fields">
  <legend>{$mod->Lang('auth_iam_role')}</legend>
  <div class="pageoverflow">
    <p class="pageinput">
      <small>{$mod->Lang('help_iam_role')}</small>
    </p>
  </div>
</fieldset>

<div class="pageoverflow">
  <p class="pageinput">
    <input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}" />
  </p>
</div>
{form_end}

<script>
(function() {
  var sel = document.getElementById('awssdk_auth_method');
  function toggle() {
    var v = sel.value;
    document.getElementById('awssdk_credentials_fields').style.display = (v === 'credentials') ? '' : 'none';
    document.getElementById('awssdk_profile_fields').style.display = (v === 'profile') ? '' : 'none';
    document.getElementById('awssdk_iam_fields').style.display = (v === 'iam_role') ? '' : 'none';
  }
  sel.onchange = toggle;
  toggle();
})();
</script>

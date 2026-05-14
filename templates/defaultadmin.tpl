{form_start}
{assign var='auth_method' value=$mod->GetPreference('auth_method', 'credentials')}

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
  <div style="display: flex; align-items: center;">
    <img src="{$mod->GetModuleURLPath()}/assets/icon.svg" alt="AWSSDK" style="width: 96px; height: 96px; margin-right: 10px;">
    <div>
      <h2 style="margin: 0;">{$mod->GetFriendlyName()}</h2>
      <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">{$mod->Lang('admindescription')}</p>
    </div>
  </div>
  <a href="https://pixelsolutions.biz" target="_blank" rel="noopener noreferrer">
    <img src="https://cdn.pixelsolutions.biz/logos/LOGO_3_COLOR_300.png" alt="Pixel Solutions" style="height: 40px;">
  </a>
</div>

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
    <input type="button" id="btn_test_connection" value="{$mod->Lang('test_connection')}" class="ui-button ui-corner-all" />
  </p>
</div>

<fieldset id="awssdk_test_results" style="display:none;margin-top:15px;">
  <legend>{$mod->Lang('test_connection')}</legend>
  <div id="awssdk_test_output"></div>
</fieldset>

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

  // Test Connection button
  var btn = document.getElementById('btn_test_connection');
  var resultsBox = document.getElementById('awssdk_test_results');
  var output = document.getElementById('awssdk_test_output');

  btn.onclick = function() {
    btn.disabled = true;
    btn.value = '{$mod->Lang("testing")}...';
    output.innerHTML = '<p><em>{$mod->Lang("testing")}...</em></p>';
    resultsBox.style.display = '';

    $.ajax({
      url: '{cms_action_url action="test_connection" forjs=1}',
      type: 'POST',
      dataType: 'json'
    }).done(function(data) {
      if (data.success) {
        var html = '<p style="color:green;font-weight:bold;">{$mod->Lang("connection_success")}</p>';
        html += '<p>{$mod->Lang("buckets_found")}: ' + data.count + '</p>';
        if (data.buckets.length > 0) {
          html += '<ul>';
          for (var i = 0; i < data.buckets.length; i++) {
            html += '<li>' + data.buckets[i] + '</li>';
          }
          html += '</ul>';
        }
        output.innerHTML = html;
      } else {
        output.innerHTML = '<p style="color:red;"><strong>{$mod->Lang("error")}:</strong> ' + data.error + '</p>';
      }
    }).fail(function(xhr, status, err) {
      output.innerHTML = '<p style="color:red;"><strong>{$mod->Lang("error")}:</strong> ' + err + '</p>';
    }).always(function() {
      btn.disabled = false;
      btn.value = '{$mod->Lang("test_connection")}';
    });
  };
})();
</script>

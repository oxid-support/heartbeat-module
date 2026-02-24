[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{assign var="settings" value=$oView->getSettings()}]
[{assign var="isActive" value=$settings.componentActive}]
[{assign var="apiUserSetupComplete" value=$oView->isApiUserSetupComplete()}]

<link rel="stylesheet" href="[{$oViewConf->getModuleUrl('oxsheartbeat', 'css/heartbeat.css')}]">

[{* Page Header with Status Badge *}]
<h1>
    [{oxmultilang ident="OXSHEARTBEAT_LF_REQUESTLOGGER_TITLE"}]
    <span class="component-status [{$oView->getStatusClass()}]">
        [{oxmultilang ident=$oView->getStatusTextKey()}]
    </span>
</h1>
<p style="color: #666; margin-bottom: 20px;">[{oxmultilang ident="OXSHEARTBEAT_LF_REQUESTLOGGER_DESC"}]</p>

[{* Toggle Section *}]
<form method="post" action="[{$oViewConf->getSelfLink()}]" id="toggleForm">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="heartbeat_requestlogger_settings">
    <input type="hidden" name="fnc" value="toggleComponent">
    <input type="hidden" name="reloadnav" value="1">

    <div class="toggle-section">
        <div>
            <h3>[{oxmultilang ident="OXSHEARTBEAT_LF_COMPONENT_ACTIVATION"}]</h3>
        </div>
        <label class="toggle-switch">
            <input type="checkbox"
                   [{if $isActive}]checked[{/if}]
                   onchange="this.form.submit();">
            <span class="toggle-slider"></span>
        </label>
    </div>
</form>

[{if !$apiUserSetupComplete}]
    <div class="info-notice">
        <h3>&#9432; [{oxmultilang ident="OXSHEARTBEAT_REQUESTLOGGER_WARNING_TITLE"}]</h3>
        <p>[{oxmultilang ident="OXSHEARTBEAT_REQUESTLOGGER_WARNING_TEXT"}]</p>
        <button type="button" class="btn-primary" onclick="top.basefrm.location='[{$oViewConf->getSelfLink()|replace:"&amp;":"&"}]&cl=heartbeat_apiuser_setup';">
            [{oxmultilang ident="OXSHEARTBEAT_REQUESTLOGGER_GOTO_APIUSER"}]
        </button>
    </div>
[{/if}]

<div>
    <form method="post" action="[{$oViewConf->getSelfLink()}]" id="settingsForm">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="heartbeat_requestlogger_settings">
        <input type="hidden" name="fnc" value="save">

        <div class="settings-group">
            <h3>[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_ACTIVATION"}]</h3>

            <div class="setting-row">
                <label class="setting-label">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_LOG_FRONTEND"}]</label>
                <div class="setting-input">
                    <input type="checkbox" name="editval[logFrontend]" value="1" [{if $settings.logFrontend}]checked[{/if}]>
                    <div class="setting-help">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_LOG_FRONTEND_HELP"}]</div>
                </div>
            </div>

            <div class="setting-row">
                <label class="setting-label">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_LOG_ADMIN"}]</label>
                <div class="setting-input">
                    <input type="checkbox" name="editval[logAdmin]" value="1" [{if $settings.logAdmin}]checked[{/if}]>
                    <div class="setting-help">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_LOG_ADMIN_HELP"}]</div>
                </div>
            </div>
        </div>

        <div class="settings-group">
            <h3>[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_LOGGING"}]</h3>

            <div class="setting-row">
                <label class="setting-label">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_DETAILED_LOGGING"}]</label>
                <div class="setting-input">
                    <input type="hidden" name="editval[logLevel]" value="standard">
                    <input type="checkbox" name="editval[logLevel]" value="detailed" [{if $settings.logLevel == 'detailed'}]checked[{/if}]>
                    <div class="setting-help">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_DETAILED_LOGGING_HELP"}]</div>
                </div>
            </div>

            <div class="setting-row">
                <label class="setting-label">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_REDACT_ALL"}]</label>
                <div class="setting-input">
                    <input type="checkbox" name="editval[redactAllValues]" value="1" [{if $settings.redactAllValues}]checked[{/if}]>
                    <div class="setting-help">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_REDACT_ALL_HELP"}]</div>
                </div>
            </div>
        </div>

        <div class="settings-group">
            <h3>[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_REDACTION"}]</h3>

            <div class="setting-row">
                <label class="setting-label">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_REDACT_FIELDS"}]</label>
                <div class="setting-input">
                    <textarea name="editval[redactFields]">[{"\n"|implode:$settings.redactFields}]</textarea>
                    <div class="setting-help">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_REDACT_FIELDS_HELP"}]</div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save" id="saveBtn">[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_SAVE"}]</button>
    </form>
</div>

<script>
(function() {
    var form = document.getElementById('settingsForm');
    var saveBtn = document.getElementById('saveBtn');
    var originalValues = {};
    var unsavedText = '[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_SAVE"}] *';
    var savedText = '[{oxmultilang ident="OXSHEARTBEAT_LF_SETTINGS_SAVE"}]';

    function storeOriginalValues() {
        var inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(function(input) {
            var key = input.name + '_' + input.type;
            if (input.type === 'checkbox') {
                originalValues[key] = input.checked;
            } else if (input.type !== 'hidden') {
                originalValues[key] = input.value;
            }
        });
    }

    function hasChanges() {
        var inputs = form.querySelectorAll('input, textarea, select');
        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i];
            var key = input.name + '_' + input.type;
            if (input.type === 'checkbox') {
                if (originalValues[key] !== input.checked) return true;
            } else if (input.type !== 'hidden') {
                if (originalValues[key] !== input.value) return true;
            }
        }
        return false;
    }

    function updateButtonState() {
        if (hasChanges()) {
            saveBtn.classList.add('unsaved');
            saveBtn.textContent = unsavedText;
        } else {
            saveBtn.classList.remove('unsaved');
            saveBtn.textContent = savedText;
        }
    }

    storeOriginalValues();
    form.addEventListener('change', updateButtonState);
    form.addEventListener('input', updateButtonState);
})();
</script>

[{include file="bottomitem.tpl"}]

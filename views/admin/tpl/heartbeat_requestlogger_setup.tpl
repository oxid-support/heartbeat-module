[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{assign var="isActive" value=$oView->isComponentActive()}]
[{assign var="apiUserSetupComplete" value=$oView->isApiUserSetupComplete()}]
[{assign var="configAccessActivated" value=$oView->isConfigAccessActivated()}]
[{assign var="canToggle" value=$oView->canToggle()}]

<link rel="stylesheet" href="[{$oViewConf->getModuleUrl('oxsheartbeat', 'css/heartbeat.css')}]">

[{* Page Header with Status Badge *}]
<h1>
    [{oxmultilang ident="OXSHEARTBEAT_LF_REMOTE_TITLE"}]
    <span class="component-status [{$oView->getStatusClass()}]">
        [{oxmultilang ident=$oView->getStatusTextKey()}]
    </span>
</h1>
<p style="color: #666; margin-bottom: 20px;">[{oxmultilang ident="OXSHEARTBEAT_LF_REMOTE_DESC"}]</p>

[{* Toggle Section *}]
<form method="post" action="[{$oViewConf->getSelfLink()}]" id="toggleForm">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="heartbeat_requestlogger_setup">
    <input type="hidden" name="fnc" value="toggleComponent">
    <input type="hidden" name="reloadnav" value="1">

    <div class="toggle-section [{if !$canToggle}]disabled[{/if}]">
        <div>
            <h3>[{oxmultilang ident="OXSHEARTBEAT_LF_COMPONENT_ACTIVATION"}]</h3>
        </div>
        <label class="toggle-switch">
            <input type="checkbox"
                   [{if $isActive && $canToggle}]checked[{/if}]
                   [{if !$canToggle}]disabled[{/if}]
                   onchange="this.form.submit();">
            <span class="toggle-slider [{if !$canToggle}]disabled[{/if}]"></span>
        </label>
    </div>
</form>

[{if !$apiUserSetupComplete}]
    <div class="warning-notice">
        <h3>&#9888; [{oxmultilang ident="OXSHEARTBEAT_REMOTE_WARNING_TITLE"}]</h3>
        <p>[{oxmultilang ident="OXSHEARTBEAT_REMOTE_WARNING_TEXT"}]</p>
        <button type="button" class="btn-primary" onclick="top.basefrm.location='[{$oViewConf->getSelfLink()|replace:"&amp;":"&"}]&cl=heartbeat_apiuser_setup';">
            [{oxmultilang ident="OXSHEARTBEAT_REMOTE_GOTO_APIUSER"}]
        </button>
    </div>
[{elseif !$configAccessActivated}]
    <div class="prerequisite-notice">
        <h3>&#9888; [{oxmultilang ident="OXSHEARTBEAT_REMOTE_CONFIG_ACCESS_REQUIRED_TITLE"}]</h3>
        <p>[{oxmultilang ident="OXSHEARTBEAT_REMOTE_CONFIG_ACCESS_REQUIRED_TEXT"}]</p>
        <p style="margin-top: 10px;"><code>./vendor/bin/oe-console oe:module:activate oe_graphql_configuration_access</code></p>
    </div>
[{elseif $isActive}]
    <div class="ready-notice">
        <h3>&#10004; [{oxmultilang ident="OXSHEARTBEAT_REMOTE_READY_TITLE"}]</h3>
        <p>[{oxmultilang ident="OXSHEARTBEAT_REMOTE_READY_TEXT"}]</p>
    </div>
[{/if}]

[{include file="bottomitem.tpl"}]

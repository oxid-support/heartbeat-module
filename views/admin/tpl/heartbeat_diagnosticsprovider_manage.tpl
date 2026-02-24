[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{assign var="isActive" value=$oView->isComponentActive()}]
[{assign var="apiUserSetupComplete" value=$oView->isApiUserSetupComplete()}]
[{assign var="canToggle" value=$oView->canToggle()}]

<link rel="stylesheet" href="[{$oViewConf->getModuleUrl('oxsheartbeat', 'out/admin/css/heartbeat.css')}]">

[{* Page Header with Status Badge *}]
<h1>
    [{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_TITLE"}]
    <span class="component-status [{$oView->getStatusClass()}]">
        [{oxmultilang ident=$oView->getStatusTextKey()}]
    </span>
</h1>
<p style="color: #666; margin-bottom: 20px;">[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_DESC"}]</p>

[{* Toggle Section *}]
<form method="post" action="[{$oViewConf->getSelfLink()}]" id="toggleForm">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="heartbeat_diagnosticsprovider_manage">
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
        <h3>&#9888; [{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_WARNING_TITLE"}]</h3>
        <p>[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_WARNING_TEXT"}]</p>
        <button type="button" class="btn-primary" onclick="top.basefrm.location='[{$oViewConf->getSelfLink()|replace:"&amp;":"&"}]&cl=heartbeat_apiuser_setup';">
            [{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_GOTO_APIUSER"}]
        </button>
    </div>
[{elseif $isActive}]
    <div class="ready-notice">
        <h3>&#10004; [{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_READY_TITLE"}]</h3>
        <p>[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_READY_TEXT"}]</p>
    </div>
[{/if}]

<div class="settings-group">
    <h3>[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_INFO_TITLE"}]</h3>
    <div class="info-content">
        <p>[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_INFO_TEXT"}]</p>
        <ul>
            <li>[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_INFO_SHOPDETAILS"}]</li>
            <li>[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_INFO_MODULES"}]</li>
            <li>[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_INFO_SYSTEM"}]</li>
            <li>[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_INFO_PHP"}]</li>
            <li>[{oxmultilang ident="OXSHEARTBEAT_DIAGNOSTICSPROVIDER_INFO_SERVER"}]</li>
        </ul>
    </div>
</div>

[{include file="bottomitem.tpl"}]

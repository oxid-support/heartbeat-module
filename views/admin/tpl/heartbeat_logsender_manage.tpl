[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{assign var="isActive" value=$oView->isComponentActive()}]
[{assign var="apiUserSetupComplete" value=$oView->isApiUserSetupComplete()}]
[{assign var="canToggle" value=$oView->canToggle()}]
[{assign var="logSources" value=$oView->getLogSources()}]
[{assign var="availableCount" value=$oView->getAvailableSourceCount()}]
[{assign var="totalCount" value=$oView->getTotalSourceCount()}]

<link rel="stylesheet" href="[{$oViewConf->getModuleUrl('oxsheartbeat', 'out/admin/css/heartbeat.css')}]">

[{* Page Header with Status Badge *}]
<h1>
    [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_TITLE"}]
    <span class="component-status [{$oView->getStatusClass()}]">
        [{oxmultilang ident=$oView->getStatusTextKey()}]
    </span>
</h1>
<p style="color: #666; margin-bottom: 20px;">[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_DESC"}]</p>

[{* Toggle Section *}]
<form method="post" action="[{$oViewConf->getSelfLink()}]" id="toggleForm">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="heartbeat_logsender_manage">
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
        <h3>&#9888; [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_WARNING_TITLE"}]</h3>
        <p>[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_WARNING_TEXT"}]</p>
        <button type="button" class="btn-primary" onclick="top.basefrm.location='[{$oViewConf->getSelfLink()|replace:"&amp;":"&"}]&cl=heartbeat_apiuser_setup';">
            [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_GOTO_APIUSER"}]
        </button>
    </div>
[{elseif $isActive}]
    <div class="ready-notice">
        <h3>&#10004; [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_READY_TITLE"}]</h3>
        <p>[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_READY_TEXT"}]</p>
    </div>
[{/if}]

[{* Log Sources Overview with integrated validation *}]
<div class="settings-group">
    <div class="settings-group-header-with-action">
        <h3>[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_SOURCES_TITLE"}] ([{$availableCount}]/[{$totalCount}])</h3>
        <form method="post" action="[{$oViewConf->getSelfLink()}]" class="header-action-form">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="cl" value="heartbeat_logsender_manage">
            <input type="hidden" name="fnc" value="refreshSources">
            <button type="submit" class="btn-refresh" title="[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_REFRESH_TITLE"}]">&#8635;</button>
        </form>
    </div>

    [{if $logSources|count > 0}]
        <div class="log-sources-list">
            [{foreach from=$logSources item=source}]
                <div class="log-source-item status-[{if $source.available}]ok[{else}]error[{/if}] [{if $source.enabled}]enabled[{else}]disabled[{/if}]">
                    <div class="log-source-header">
                        <span class="log-source-icon">
                            [{if $source.available}]
                                &#10004;
                            [{else}]
                                &#10008;
                            [{/if}]
                        </span>
                        <span class="log-source-name">[{$source.name}]</span>
                        <span class="log-source-origin">([{if $source.origin == 'provider'}]Provider[{else}]Static[{/if}])</span>
                        <form method="post" action="[{$oViewConf->getSelfLink()}]" class="source-toggle-form" id="toggle-form-[{$source.id}]">
                            [{$oViewConf->getHiddenSid()}]
                            <input type="hidden" name="cl" value="heartbeat_logsender_manage">
                            <input type="hidden" name="fnc" value="toggleSource">
                            <input type="hidden" name="sourceId" value="[{$source.id}]">
                            <button type="submit" class="source-toggle-btn [{if $source.enabled}]enabled[{/if}]" [{if !$source.available}]disabled[{/if}] title="[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_TOGGLE_SOURCE"}]">
                                <span class="source-toggle-slider [{if !$source.available}]disabled[{/if}]"></span>
                                <span class="source-toggle-knob"></span>
                            </button>
                        </form>
                    </div>
                    [{if $source.description}]
                        <div class="log-source-description">[{$source.description}]</div>
                    [{/if}]
                    [{foreach from=$source.paths item=path}]
                        [{assign var="validation" value=$path.validation}]
                        <div class="log-source-path status-[{$validation.status}]">
                            <div class="log-source-path-main">
                                <span class="log-source-type-icon" title="[{if $path.type == 'directory'}][{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_TYPE_DIRECTORY"}][{else}][{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_TYPE_FILE"}][{/if}]">[{if $path.type == 'directory'}]&#128193;[{else}]&#128196;[{/if}]</span>
                                <code>[{$path.path}]</code>
                                [{if $path.filePattern}]
                                    <span class="log-source-pattern">Pattern: [{$path.filePattern}]</span>
                                [{/if}]
                            </div>
                            <div class="log-source-path-validation">
                                [{if $validation.error == 'PATH_NOT_FOUND'}]
                                    <span class="validation-error">&#10008; [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_ERROR_NOT_FOUND"}]</span>
                                [{elseif $validation.error == 'NOT_READABLE'}]
                                    <span class="validation-error">&#10008; [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_ERROR_NOT_READABLE"}]</span>
                                [{elseif $validation.error == 'CANNOT_LIST_DIRECTORY'}]
                                    <span class="validation-warning">&#9888; [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_ERROR_CANNOT_LIST"}]</span>
                                [{else}]
                                    [{if $path.type == 'directory'}]
                                        <span class="validation-info">&#10004; [{$validation.fileCount}] [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_FILES_FOUND"}]</span>
                                    [{else}]
                                        <span class="validation-info">&#10004; [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_SIZE"}]: [{$validation.fileSize/1024|string_format:"%.1f"}] KB</span>
                                    [{/if}]
                                [{/if}]
                            </div>
                        </div>
                    [{/foreach}]
                </div>
            [{/foreach}]
        </div>
    [{else}]
        <p class="no-sources">[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_NO_SOURCES"}]</p>
    [{/if}]
</div>

[{* Static Paths Configuration with inline help *}]
<div class="settings-group">
    <h3>[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_STATIC_TITLE"}]</h3>

    <form method="post" action="[{$oViewConf->getSelfLink()}]">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="heartbeat_logsender_manage">
        <input type="hidden" name="fnc" value="saveStaticPaths">

        <div class="form-group">
            <textarea
                name="staticPaths"
                id="staticPaths"
                class="static-paths-textarea"
                rows="4"
                placeholder="[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_STATIC_PATHS_PLACEHOLDER"}]"
            >[{$oView->getStaticPathsText()}]</textarea>
        </div>

        <button type="submit" class="btn-primary">[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_SAVE"}]</button>
    </form>

    <details class="howto-section">
        <summary><span class="help-icon">[?]</span> [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_HOWTO_TITLE"}]</summary>
        <div class="howto-content">
            <p>[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_HOWTO_TEXT"}]</p>
            <ul>
                <li><strong>[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_HOWTO_PROVIDER"}]:</strong> [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_HOWTO_PROVIDER_DESC"}]</li>
                <li><strong>[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_HOWTO_STATIC"}]:</strong> [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_HOWTO_STATIC_DESC"}]</li>
                <li><strong>[{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_HOWTO_REFRESH"}]:</strong> [{oxmultilang ident="OXSHEARTBEAT_LOGSENDER_HOWTO_REFRESH_DESC"}]</li>
            </ul>
        </div>
    </details>
</div>

[{include file="bottomitem.tpl"}]

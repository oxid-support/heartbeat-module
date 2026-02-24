[{$smarty.block.parent}]

[{* Inject CSS for status indicators and menu styling *}]
<style>
    .lf-status-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 5px;
        vertical-align: middle;
    }
    .lf-status-indicator.active { background-color: #4caf50; }
    .lf-status-indicator.inactive { background-color: #9e9e9e; }
    /* Prevent menu items from wrapping */
    #nav li a b { white-space: nowrap; }
</style>

[{* Script to restore navigation state after component toggle reload *}]
<script>
(function() {
    var expandNav = localStorage.getItem('oxs_lf_expand_nav');
    if (expandNav) {
        localStorage.removeItem('oxs_lf_expand_nav');
        // Use timeout to ensure navigation functions are loaded
        setTimeout(function() {
            if (typeof _navExtExpActByName === 'function') {
                _navExtExpActByName(expandNav);
            }
        }, 100);
    }

    [{* Add status indicators to navigation items *}]
    [{if $lfComponentStatus}]
    var componentStatus = {
        [{foreach from=$lfComponentStatus key=cl item=status name=statusLoop}]
        '[{$cl}]': [{if $status}]true[{else}]false[{/if}][{if !$smarty.foreach.statusLoop.last}],[{/if}]
        [{/foreach}]
    };

    // Add status indicators to menu items
    for (var cl in componentStatus) {
        var menuItem = document.querySelector('[name="nav_' + cl + '"] a b');
        if (menuItem) {
            var indicator = document.createElement('span');
            indicator.className = 'lf-status-indicator ' + (componentStatus[cl] ? 'active' : 'inactive');
            menuItem.insertBefore(indicator, menuItem.firstChild);
        }
    }
    [{/if}]
})();
</script>

<!DOCTYPE html>
<html>
<head>
    <title>[{block name="title"}][{/block}]</title>
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('oxsheartbeat', 'css/heartbeat.css')}]">
    [{block name="head"}][{/block}]
</head>
<body>
[{block name="body"}][{/block}]

[{block name="scripts"}][{/block}]

[{if $oView->getViewParameter('reloadnav')}]
<script>
if (top.basefrm && top.basefrm.navigation) {
    top.basefrm.navigation.location.reload();
}
</script>
[{/if}]
</body>
</html>

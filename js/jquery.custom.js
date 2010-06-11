function applyLayout()
{
    $('body').layout(
    {
        defaults:
        {
            applyDefaultStyles: true,
            center__onresize: "innerLayout.resizeAll"
        },
        north:
        {
            closable: false,
            resizable: false,
            size: '103',
            spacing_open: 0
        },
        west:
        {
            //TODO: Add options here
        },
        east:
        {
            //TODO: Add options here
        },
        south:
        {
            initClosed: true
        }

    });


}

function applyLayout()
{
    return $('body').layout(
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
            size: '50',
            spacing_open: 0,
            applyDefaultStyles: false,
            paneClass: 'north-pane'
        },
        center:
        {
            applyDefaultStyles: false,
            paneClass: 'center-pane'
        },
        west:
        {
            applyDefaultStyles: false,
            paneClass: 'west-pane',
            resizerClass: 'west-resizer',
            togglerClass: 'ui-layout-toggler',
            buttonClass: 'ui-layout-button'
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

function navigationPanels()
{
    $(".side-pane > h1").css("cursor", "pointer");
    $(".side-pane > h1").click(
        function()
        {
            $(this).siblings("ul").slideToggle();
        }
    );

}

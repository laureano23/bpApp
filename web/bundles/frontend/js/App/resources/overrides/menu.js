Ext.define('MetApp.resources.overrides.menu', {
	override: 'Ext.menu.Menu',

    compatibility : '4',

    onMouseLeave: function(e) {
        var me = this;

        // If the mouseleave was into the active submenu, do not dismiss
        if (me.activeChild) {
            if (e.within(me.activeChild.el, true)) {
                return;
            }
        }
        me.deactivateActiveItem();
        if (me.disabled) {
            return;
        }
        me.fireEvent('mouseleave', me, e);
    }
});

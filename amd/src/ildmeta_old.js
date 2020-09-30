// Standard license block omitted.
/*
 * @package    ildmeta
 * @author     Markus Strehling <markus.strehling@oncampus.de)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 /**
  * @module block_ildmetaselect/ildmetaselect
  */

 Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

define(['jquery'], function($) {

    var select;
    var hidden;
    var show;
    var options;

    function get_dif_array(array1, array2){
        var backarray = new Array();
        array1.forEach(function(id){
            var found = false;
            array2.forEach(function(selectid){
                if(id == selectid){
                    found = true;
                }
            });
            if(!found){
                backarray.push(id);
            }
        });
        return backarray;
    }

    function select_uni(){
        var hiddenids = hidden.val();
        var ids = hiddenids.split(',');

        var selected = select.val();

        var newids = get_dif_array(selected, ids);
        var delids = get_dif_array(ids, selected);

        newids.forEach(function(id){
            ids.push(id);
        });

        delids.forEach(function(id){
            ids.remove(id);
        });

        hidden.val(ids.join(','));

        clear_show();
        
        ids.forEach(function(id){
            add_element_show(options[id].text, id);
        });

        check_show();
        
    }

    function check_show(){
        var count = show.children().length;
        if(count === 0){
            show.append("<span>Keine Einträge</span>");
        }
    }

    function remove_entry(id){
        var hiddenids = hidden.val();
        var ids = hiddenids.split(',');
        ids.remove(id);

        $(options[id]).prop("selected", false);

        hidden.val(ids.join(','));

        select_uni();
    }

    function clear_show(){
        show.empty();
    }

    function add_element_show(name, id){
        show.append("<span class='unilist-show-element' data-value='" + id + "' aria-selected='true'><span class='unilist-remove'>X</span>" + name + "</span>");

        var obj = $('span.unilist-show-element[data-value="'+id+'"]');
        obj.click(function(){
            remove_entry($(this).attr('data-value'));
        });
    }

    return {
        init: function() {
            select = $("select#id_universitylist");
            show = $('div#unilist');
            hidden = $('input[name="university"]');
            options = $("select#id_universitylist option");

            clear_show();

            var selected = hidden.val().split(',');
            selected.forEach(function(id){
                add_element_show(options[id].text, id);
            });

            select.change(function(event){
                select_uni();
            });
        }
    };
});
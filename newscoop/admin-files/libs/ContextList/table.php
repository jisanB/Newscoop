<?php
/**
 * @package Campsite
 *
 * @author Vlad Nicoara <vlad.nicoara@sourcefabric.org>
 * @copyright 2010 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl.txt
 * @link http://www.sourcefabric.org
 */
?>
<input id="search_table_id" type="hidden" value="table-<?php echo $this->id; ?>" />
<div class="table">

<table id="table-<?php echo $this->id; ?>" cellpadding="0" cellspacing="0" class="datatable">

</table>
</div>

<?php if (!self::$renderTable) { ?>
<script type="text/javascript"><!--
tables = [];
filters = [];

function sendOrder(form, hash)
{
    var order = $('#table-' + hash + ' tbody').sortable('toArray');
    callServer(['ArticleList', 'doOrder'], [
        order,
        $('input[name=language]', $(form)).val(),
        ], function(data) {
            tables[hash].fnSort([[2, 'asc']]);
            tables[hash].fnDraw(true);
            flashMessage('<?php putGS('Order updated.'); ?>');
        });
    return false;
}
--></script>
<?php } // render ?>
<script type="text/javascript"><!--
$(document).ready(function() {
var table = $('#table-<?php echo $this->id; ?>');
filters['<?php echo $this->id; ?>'] = [];

tables['<?php echo $this->id; ?>'] = table.dataTable({
	'bLengthChange': false,
    'bAutoWidth': true,
    'bScrollCollapse': true,
    'bssDestroy': true,
    'sDom': '<?php echo $this->getContextSDom(); ?>',
    'oLanguage': {
        'oPaginate': {
            'sFirst': '<?php putGS('First'); ?>',
            'sNext': '<?php putGS('Next'); ?>',
            'sPrevious': '<?php putGS('Previous'); ?>',
            'sLast': '<?php putGS('Last'); ?>',
        },

        'sZeroRecords': '<?php putGS('No records found.'); ?>',
        'sSearch': '',
        'sInfo': '<?php putGS('Showing _START_ to _END_ of _TOTAL_ entries'); ?>',
        'sEmpty': '<?php putGS('No entries to show'); ?>',
        'sInfoFiltered': '<?php putGS(' - filtering from _MAX_ records'); ?>',
        'sLengthMenu': '<?php putGS('Display _MENU_ records'); ?>',
        'sInfoEmpty': '',
    },
    'aoColumnDefs': [
        { // inputs for id
            'fnRender': function(obj) {
                var id = obj.aData[0] + '_' + obj.aData[1];
                return '<input type="checkbox" name="' + id + '" />';
            },
            'aTargets': [0]
        },

        { // hide columns
            'bVisible': false,
            'aTargets': [0,1]
        },
        { // not sortable
            'bSortable': false,
            'aTargets': [0, 1, 2]
        },
        { // id
            'sClass': 'id',
            'sWidth': '3em',
            'aTargets': [0]
        },
        { // name
            'sClass': 'name',
            'sWidth': '13em',
            'aTargets': [2]
        },
    ],
    'fnDrawCallback': function() {
        $('#table-<?php echo $this->id; ?> tbody tr').click(function(event) {
            if (event.target.type == 'checkbox') {
                return; // checkbox click, handled by it's change
            }

            var input = $('input:checkbox', $(this));
            if (input.attr('checked')) {
                input.removeAttr('checked');
            } else {
                input.attr('checked', 'checked');
            }
            input.change();
        }).each(function() {
            var tr = $(this);
            // detect locks
            if ($('.name .ui-icon-locked', tr).not('.current-user').size()) {
                tr.addClass('locked');
            }
        });

        $('#table-<?php echo $this->id; ?> tbody input:checkbox').change(function() {
            if ($(this).attr('checked')) {
                $(this).parents('tr').addClass('selected');
            } else {
                $(this).parents('tr').removeClass('selected');
            }

            // update check all checkbox on item change
            var table = $('#table-<?php echo $this->id; ?>');
            if ($('tbody input:checkbox', table).size() == $('tbody input:checkbox:checked', table).size()) { // all checked
                $('.smartlist thead input:checkbox').attr("checked", true);
            } else {
                $('.smartlist thead input:checkbox').attr("checked", false);
            }
        });

        <?php if ($this->order) { ?>


        <?php } ?>
    },
    <?php if ($this->items !== NULL) { // display all items ?>
    'bPaging': false,
    'iDisplayLength': 3,
    <?php } else { // no items - server side ?>
    'bPaging': true,
    'bServerSide': true,
    'iDisplayLength' : 3,
    'sAjaxSource': '<?php echo $this->path; ?>/do_data.php',
    'sPaginationType': 'full_numbers',
    'fnServerData': function (sSource, aoData, fnCallback) {
        for (var i in filters['<?php echo $this->id; ?>']) {
            aoData.push({
                'name': i,
                'value': filters['<?php echo $this->id; ?>'][i],
            });
        }
        <?php foreach (array('publication', 'issue', 'section', 'language') as $filter) {
            if ($filter == 'language' && !$this->order) {
                continue; // ignore language on non-section pages
            }

            if (!empty($this->$filter)) { ?>
            aoData.push({
                'name': '<?php echo $filter; ?>',
                'value': '<?php echo $this->$filter; ?>',
            });
        <?php }} ?>
            callServer(['ContextList', 'doData'], aoData, fnCallback);
    },
    'fnStateLoadCallback': function(oSettings, oData) {
        oData.sFilter = ''; // reset filter
        <?php if ($this->order) { ?>
        oData.aaSorting = [[2, 'asc']]; // show correct order on reload
        <?php } ?>
        return true;
    },
    <?php } ?>
    <?php if ($this->colVis) { ?>
    'oColVisx': { // disable Show/hide column
        //'aiExclude': [0, 1, 2],
       // 'buttonText': '<?php putGS('Show / hide columns'); ?>',
    },
    <?php } ?>
    <?php if ($this->order) { ?>
    'fnRowCallback': function(nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        var id = $(aData[0]).attr('name').split('_')[0];
        $(nRow).attr('id', 'article_' + id);
        return nRow;
    },
    <?php } ?>
    'bJQueryUI': true
}).css('position', 'relative').css('width', '100%');

});
--></script>
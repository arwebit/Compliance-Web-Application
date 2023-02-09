<script src="bower_components/jquery/dist/jquery.js"></script>

<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="dist/js/pages/dashboard.js"></script>
<script src="dist/js/demo.js"></script>
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="dist/js/table2excel.js" type="text/javascript"></script>

<script>
    $(function () {
        $('#stat_assgn_task').DataTable({
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    //added class "mymsel"
                    var select = $('<select class="mymsel form-control select2" style="width:90px; border:2px solid black;" multiple="multiple"><option value=""></option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var vals = $('option:selected', this).map(function (index, element) {
                                    return $.fn.dataTable.util.escapeRegex($(element).val());
                                }).toArray().join('|');

                                column
                                        .search(vals.length > 0 ? '^(' + vals + ')$' : '', true, false)
                                        .draw();
                            });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                //select2 init for .mymsel class
                $(".mymsel").select2();
            }
        });

        $('#nonstat_assgn_task').DataTable({
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    //added class "mymsel"
                    var select = $('<select class="mymsel form-control select2" style="width:90px; border:2px solid black;" multiple="multiple"><option value=""></option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function () {
                                var vals = $('option:selected', this).map(function (index, element) {
                                    return $.fn.dataTable.util.escapeRegex($(element).val());
                                }).toArray().join('|');

                                column
                                        .search(vals.length > 0 ? '^(' + vals + ')$' : '', true, false)
                                        .draw();
                            });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                //select2 init for .mymsel class
                $(".mymsel").select2();
            }
        });




        $('#example1').DataTable();
        $('#example2').DataTable();
        $('#example3').DataTable();
        $("#btnExporttoExcel").click(function () {
            $("#tblData").table2excel({
                filename: "<?php echo $excel_file_name; ?>.xls",
                sheetName: "<?php echo $excel_file_name; ?>"
            });
        });
        $("#btnExportSC").click(function () {
            $("#statutory_data").table2excel({
                filename: "Statutory.xls",
                sheetName: "Statutory"
            });
        });
        $("#btnExportNSC").click(function () {
            $("#non_statutory_data").table2excel({
                filename: "Non-statutory.xls",
                sheetName: "Non-statutory"
            });
        });
    })
</script>
<!-- Page script -->
<script>
    $(function () {
        $('.select2').select2()
        $('#datemask').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'})
        $('[data-mask]').inputmask()
        $('#reservation').daterangepicker()
        $('#reservation').val("")
        $('#datepicker').datepicker({
            autoclose: true
        })
    })
</script>
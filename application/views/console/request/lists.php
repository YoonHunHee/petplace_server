<div class="row">
    <div class="col-md-12 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Request List</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
            <form name="wform" method="post" action="/console/friends/del">
            <input type="hidden" name="id"/>

                <table class="table table-striped responsive-utilities jambo_table bulk_action">
                    <colgroup>
                        <col width="30px"/>
                        <col width="70px"/>
                        <col width="150px"/>
                        <col width="120px"/>
                        <col width="*">
                        <col width="90px"/>
                        <col width="80px"/>
                    </colgroup>
                    <thead>
                        <tr class="headings">
                            <th>
                                <input type="checkbox" id="check-all" class="flat">
                            </th>
                            <th class="column-title">Kind</th>
                            <th class="column-title">Title</th>
                            <th class="column-title">Tel</th>
                            <th class="column-title">Addr</th>
                            <th class="column-title">Date</th>
                            <th class="column-title no-link last"><span class="nobr">Action</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($list as $key=>$row) {?>
                        <tr class="<?=($key%2==0) ? 'even' : 'odd';?> pointer">
                            <td class="a-center "><input type="checkbox" class="flat" name="table_records" ></td>
                            <td class=" "><?=$row['kind']?></td>
                            <td class=" "><?=$row['title']?></td>
                            <td class=" "><?=$row['tel']?></td>
                            <td class=" "><?=$row['addr']?></td>
                            <td class=" "><?=substr($row['create_at'],0,10)?></td>
                            <td class=" last">
                                <a href="/console/friends/form/<?=$row['id']?>"><i class="fa fa-pencil-square-o"></i></a>
                                <a href="javascript:go_del('<?=$row['id']?>');"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php }?>
                    </tbody>

                </table>

                <div class="paging">
                    <?php echo $paging?>
                </div>

                <a href="/console/friends/form" class="btn btn-success btn-sm">Add</a>
                <a href="/console/friends/get_json" class="btn btn-success btn-sm">json</a>

                <div>
                    <table class="table table-striped responsive-utilities jambo_table bulk_action">
                    <tr>
                        <th>배포형</th>
                        <td>
                            <select>
                                <option>major</option>
                                <option>middle</option>
                                <option>minor</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>현재버젼</th>
                        <td>0.0.1 / 2015.10.08 updated.</td>
                    </tr>
                    </table>
                    <a href="javascript:release_data();" class="btn btn-info btn-sm">데이터 배포</a>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<script>
function go_del(id)
{
    if(confirm("정말로 삭제하시겠습니까?"))
    {
        var f = document.forms.wform;
        f.id.value = id;
        f.submit();
    }
}

function release_data()
{
    if(confirm("정말로 배포하시겠습니까?"))
}
</script>

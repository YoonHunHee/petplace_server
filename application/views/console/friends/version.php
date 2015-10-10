<div class="row">
    <div class="col-md-12 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Version  List</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
            <form name="wform" method="post" action="/console/friends/del">
            <input type="hidden" name="id"/>

                <table class="table table-striped responsive-utilities jambo_table bulk_action">
                    <colgroup>
                        <col width="70px"/>
                        <col width="*">
                        <col width="80px"/>
                    </colgroup>
                    <thead>
                        <tr class="headings">
                            <th class="column-title">Version</th>
                            <th class="column-title">Desc</th>
                            <th class="column-title">Date</th>
                        </tr>
                    </thead>

                    <tbody>
                <?php if(sizeof($list) > 0) {?>
                    <?php foreach ($list as $key=>$row) {?>
                        <tr class="<?=($key%2==0) ? 'even' : 'odd';?> pointer">
                            <td class="a-center"><?=$row['version']?></td>
                            <td class=" "><?=$row['desc']?></td>
                            <td class=" last"><?=substr($row['create_at'],0,10)?></td>
                        </tr>
                    <?php }?>
                <?php }else{?>
                        <tr>
                            <td class="a-center" colspan="3">[ update version이 없습니다. ] </td>
                        </tr>
                <?php }?>
                    </tbody>

                </table>

                <div>
                    <select name="update_type">
                        <option value="">선택</option>
                        <option value="major">major</option>
                        <option value="minor">minor</option>
                    </select>

                    <a href="javascript:release_update();" class="btn btn-info btn-sm">VERSION UPDATE</a>
                    <br/>
                    # 테이블 변경/수정/삭제 또는 대량의 데이터 추가 후 배포시 major 업그레이드
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<script>

function release_update()
{
    if(confirm("VERSION UPDATE 하시겠습니까?"))
    {

    }
}
</script>

<div class="row">
  <div class="col-md-12 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>User</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
            <form name="wform" method="post" action="/console/user/del">
            <input type="hidden" name="id"/>
                <table id="lists" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                        <tr class="headings">
                            <th>
                                <input type="checkbox" class="tableflat">
                            </th>
                            <th>Email</th>
                            <th>Nick Name</th>
                            <th>Date</th>
                            <th class=" no-link last"><span class="nobr">Action</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                <?php if(sizeof($list) > 0) { ?>
                    <?php foreach ($list as $key=>$row) {?>
                        <tr class="<?=($key%2==0) ? 'even' : 'odd';?> pointer">
                            <td class="a-center ">
                                <input type="checkbox" class="tableflat" value="<?=$row['id']?>">
                            </td>
                            <td class=" "><?=$row['email']?></td>
                            <td class=" "><?=$row['nick_name']?></td>
                            <td class=" "><?=substr($row['create_at'],0,10)?></td>
                            <td class=" last">
                                <a href="/console/user/form/<?=$row['id']?>"><i class="fa fa-pencil-square-o"></i></a>
                                <a href="javascript:go_del('<?=$row['id']?>');"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php }?>
                <?php } else { ?>
                        <tr>
                            <td colspan="5"> [ 가입한 사용자가 존재하지 않습니다.] </td>
                        </tr>   
                <?php }?>
                    </tbody>
                </table>

                <div class="paging">
                    <?php echo $paging?>
                </div>

                <a href="/console/user/form" class="btn btn-success btn-sm">Add</a>
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
</script>

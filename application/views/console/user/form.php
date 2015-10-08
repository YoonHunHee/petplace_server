<div class="row">
  <div class="col-md-12 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>User Form</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

              <form class="form-horizontal form-label-left" novalidate method="post" action="/console/user/action">
              <input type="hidden" name="mode" value="<?=(isset($row)) ? 'edit' : 'regist'?>"/>
                  <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="upfile">Profile </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="file" id="upfile" name="upfile" class="form-control col-md-7 col-xs-12"/>
                      </div>
                  </div>
                  <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12" value="<?=(isset($row)) ? $row->email : ''?>" <?=(isset($row)) ? 'readonly="readonly"' : ''?>/>
                      </div>
                  </div>
                  <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nick_name">Nick Name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="nick_name" name="nick_name" required="required" class="form-control col-md-7 col-xs-12" value="<?=(isset($row)) ? $row->nick_name : ''?>"/>
                      </div>
                  </div>
                  <div class="item form-group">
                      <label for="password" class="control-label col-md-3">Password</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="password" id="password" name="password" data-validate-length="6,8" class="form-control col-md-7 col-xs-12"/>
                      </div>
                  </div>
                  <div class="item form-group">
                      <label for="password_re" class="control-label col-md-3 col-sm-3 col-xs-12">Re Password</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="password" id="password_re" name="password_re" data-validate-linked="password" class="form-control col-md-7 col-xs-12"/>
                      </div>
                  </div>
                  <div class="ln_solid"></div>
                  <div class="form-group">
                      <div class="col-md-6 col-md-offset-5">
                        <a href="javascript:history.back(-1);" class="btn btn-primary">Cancel</a>
                          <button id="send" type="submit" class="btn btn-success">Submit</button>
                      </div>
                  </div>
              </form>

            </div>
        </div>
    </div>
</div>

<!-- form validation -->
<script src="/assets/libs/validator/validator.js"></script>
<script>
  validator.message['date'] = 'not a real date';


    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);
        
    // bind the validation to the form submit event
    //$('#send').click('submit');//.prop('disabled', true);

    $('form').submit(function (e) {
        e.preventDefault();
        var submit = true;
        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
            submit = false;
        }

        if (submit)
            this.submit();
        return false;
    });
</script>
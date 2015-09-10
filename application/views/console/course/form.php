<div class="row">
    <div class="col-md-12 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Course Form</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <form class="form-horizontal form-label-left" novalidate method="post" action="/console/place/action">
                <input type="hidden" name="mode" value="<?=(isset($row)) ? 'edit' : 'regist'?>"/>
                <input type="hidden" name="id" value="<?=(isset($row)) ? $row->id : ''?>"/>
                    
                    <div id="map" style="height:200px;" class="item form-group">
                        Map Loading...
                    </div>
                    
                    <div class="item form-group">
                        <a href="" class="btn btn-info btn-xs">시작 포인트</a>
                        <a href="" class="btn btn-primary btn-xs">종료 포인트</a>
                        <a href="" class="btn btn-danger btn-xs">경유 포인트</a>
                    </div>

                    <script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=8fa982632b10ed739e3b21f74732b435"></script>
                    <?php if(isset($row) && !empty($row->lat)) {?>
                    <script>
                        var container = document.getElementById('map');
                        var options = {
                            center: new daum.maps.LatLng(<?php echo $row->lat?>, <?php echo $row->lng?>),
                            level: 2
                        };

                        var map = new daum.maps.Map(container, options);
                        map.setZoomable(false);
                        map.setDraggable(false);

                        // 마커가 표시될 위치입니다 
                        var markerPosition  = new daum.maps.LatLng(<?php echo $row->lat?>, <?php echo $row->lng?>); 

                        // 마커를 생성합니다
                        var marker = new daum.maps.Marker({
                            position: markerPosition
                        });

                        // 마커가 지도 위에 표시되도록 설정합니다
                        marker.setMap(map);
                    </script>
                    <?php } else { ?>
                    <script>
                        var container = document.getElementById('map');
                        var options = {
                            center: new daum.maps.LatLng(33.450701, 126.570667),
                            level: 2
                        };

                        var map = new daum.maps.Map(container, options);

                        var startSrc = 'http://i1.daumcdn.net/localimg/localimages/07/mapapidoc/red_b.png', // 출발 마커이미지의 주소입니다    
                            startSize = new daum.maps.Size(50, 45), // 출발 마커이미지의 크기입니다 
                            startOption = { 
                                offset: new daum.maps.Point(15, 43) // 출발 마커이미지에서 마커의 좌표에 일치시킬 좌표를 설정합니다 (기본값은 이미지의 가운데 아래입니다)
                            };

                        // 출발 마커 이미지를 생성합니다
                        var startImage = new daum.maps.MarkerImage(startSrc, startSize, startOption);

                        var startDragSrc = 'http://i1.daumcdn.net/localimg/localimages/07/mapapidoc/red_drag.png', // 출발 마커의 드래그 이미지 주소입니다    
                            startDragSize = new daum.maps.Size(50, 64), // 출발 마커의 드래그 이미지 크기입니다 
                            startDragOption = { 
                                offset: new daum.maps.Point(15, 54) // 출발 마커의 드래그 이미지에서 마커의 좌표에 일치시킬 좌표를 설정합니다 (기본값은 이미지의 가운데 아래입니다)
                            };

                        // 출발 마커의 드래그 이미지를 생성합니다
                        var startDragImage = new daum.maps.MarkerImage(startDragSrc, startDragSize, startDragOption);

                        // 출발 마커가 표시될 위치입니다 
                        var startPosition = new daum.maps.LatLng(33.450701, 126.570667); 

                        // 출발 마커를 생성합니다
                        var startMarker = new daum.maps.Marker({
                            map: map, // 출발 마커가 지도 위에 표시되도록 설정합니다
                            position: startPosition,
                            draggable: true, // 출발 마커가 드래그 가능하도록 설정합니다
                            image: startImage // 출발 마커이미지를 설정합니다
                        });

                        // 출발 마커에 dragstart 이벤트를 등록합니다
                        daum.maps.event.addListener(startMarker, 'dragstart', function() {
                            // 출발 마커의 드래그가 시작될 때 마커 이미지를 변경합니다
                            startMarker.setImage(startDragImage);
                        });

                        // 출발 마커에 dragend 이벤트를 등록합니다
                        daum.maps.event.addListener(startMarker, 'dragend', function() {
                             // 출발 마커의 드래그가 종료될 때 마커 이미지를 원래 이미지로 변경합니다
                            startMarker.setImage(startImage);
                        });

                        var arriveSrc = 'http://i1.daumcdn.net/localimg/localimages/07/mapapidoc/blue_b.png', // 도착 마커이미지 주소입니다    
                        arriveSize = new daum.maps.Size(50, 45), // 도착 마커이미지의 크기입니다 
                        arriveOption = { 
                            offset: new daum.maps.Point(15, 43) // 도착 마커이미지에서 마커의 좌표에 일치시킬 좌표를 설정합니다 (기본값은 이미지의 가운데 아래입니다)
                        };

                        // 도착 마커 이미지를 생성합니다
                        var arriveImage = new daum.maps.MarkerImage(arriveSrc, arriveSize, arriveOption);

                        var arriveDragSrc = 'http://i1.daumcdn.net/localimg/localimages/07/mapapidoc/blue_drag.png', // 도착 마커의 드래그 이미지 주소입니다    
                            arriveDragSize = new daum.maps.Size(50, 64), // 도착 마커의 드래그 이미지 크기입니다 
                            arriveDragOption = { 
                                offset: new daum.maps.Point(15, 54) // 도착 마커의 드래그 이미지에서 마커의 좌표에 일치시킬 좌표를 설정합니다 (기본값은 이미지의 가운데 아래입니다)
                            };
                         
                        // 도착 마커의 드래그 이미지를 생성합니다
                        var arriveDragImage = new daum.maps.MarkerImage(arriveDragSrc, arriveDragSize, arriveDragOption);

                        // 도착 마커가 표시될 위치입니다 
                        var arrivePosition = new daum.maps.LatLng(33.450701, 126.572667);    
                         
                        // 도착 마커를 생성합니다 
                        var arriveMarker = new daum.maps.Marker({  
                            map: map, // 도착 마커가 지도 위에 표시되도록 설정합니다
                            position: arrivePosition,
                            draggable: true, // 도착 마커가 드래그 가능하도록 설정합니다
                            image: arriveImage // 도착 마커이미지를 설정합니다
                        });

                        // 도착 마커에 dragstart 이벤트를 등록합니다
                        daum.maps.event.addListener(arriveMarker, 'dragstart', function() {
                            // 도착 마커의 드래그가 시작될 때 마커 이미지를 변경합니다
                            arriveMarker.setImage(arriveDragImage);
                        });

                        // 도착 마커에 dragend 이벤트를 등록합니다
                        daum.maps.event.addListener(arriveMarker, 'dragend', function() {
                             // 도착 마커의 드래그가 종료될 때 마커 이미지를 원래 이미지로 변경합니다
                            arriveMarker.setImage(arriveImage);  
                        });
                    </script>
                    <?php } ?>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="title" name="title" required="required" class="form-control col-md-7 col-xs-12" value="<?=(isset($row)) ? $row->title : ''?>"/>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="desc" class="control-label col-md-3">간략설명</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea name="desc" id="desc" class="form-control col-md-7 col-xs-12"><?=(isset($row)) ? $row->desc : ''?></textarea>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-5">
                            <a href="javascript:history.back(-1);" class="btn btn-primary">Cancel</a>
                            <button id="send" type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div
                </form>
            </div>
        </div>
    </div>
</div>


<!-- form validation -->
<script src="/assets/libs/validator/validator.js"></script>
<script>
    $(".marker-btn").on("click", function () {
        var _name = 'addr';
        var _obj = this;
        var _txt = $(_obj).text();
        if($(this).hasClass('road_addr')) {
            _name = 'road_addr';    
        }

        if($('#' + _name).val() == '')return alert('주소를 입력하세요');

        $.ajax({
            type: 'POST',
            url: '/console/common/get_latlng',
            data: { addr : $('#' + _name).val() },
            dataType: 'json',
            beforeSend: function() {
                $(_obj).text('loading...');
            },
            success: function(result) {
                $(_obj).text(_txt);
                var container = document.getElementById('map');
                var options = {
                    center: new daum.maps.LatLng(result['lat'], result['lng']),
                    level: 2
                };

                var map = new daum.maps.Map(container, options);
            
                // 마커가 표시될 위치입니다 
                var markerPosition  = new daum.maps.LatLng(result['lat'], result['lng']); 

                // 마커를 생성합니다
                var marker = new daum.maps.Marker({
                    position: markerPosition
                });

                // 마커가 지도 위에 표시되도록 설정합니다
                marker.setMap(map);
            }
        });
    });
    
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

    function markerView(type) {
        if($('#' + type).val() == '')return alert('주소를 입력하세요');

        $.ajax({
            type: 'POST',
            url: '/console/common/get_latlng',
            data: { addr : $('#' + type).val() },
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(result) {
                var container = document.getElementById('map');
                var options = {
                    center: new daum.maps.LatLng(result['lat'], result['lng']),
                    level: 2
                };

                var map = new daum.maps.Map(container, options);
            
                // 마커가 표시될 위치입니다 
                var markerPosition  = new daum.maps.LatLng(result['lat'], result['lng']); 

                // 마커를 생성합니다
                var marker = new daum.maps.Marker({
                    position: markerPosition
                });

                // 마커가 지도 위에 표시되도록 설정합니다
                marker.setMap(map);
            }
        });
    }
</script>
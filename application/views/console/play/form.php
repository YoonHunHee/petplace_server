<style>
.dot {overflow:hidden;float:left;width:12px;height:12px;background: url('http://i1.daumcdn.net/localimg/localimages/07/mapapidoc/mini_circle.png');}
.dot_large {overflow:hidden;float:left;width:16px;height:16px;background: url('http://i1.daumcdn.net/localimg/localimages/07/mapapidoc/mini_circle.png') no-repeat;background-size: 16px 16px;}
.dotOverlay {position:relative;bottom:10px;border-radius:6px;border: 1px solid #ccc;border-bottom:2px solid #ddd;float:left;font-size:12px;padding:5px;background:#fff;}
.dotOverlay:nth-of-type(n) {border:0; box-shadow:0px 1px 2px #888;}    
.number {font-weight:bold;color:#ee6152;}
.dotOverlay:after {content:'';position:absolute;margin-left:-6px;left:50%;bottom:-8px;width:11px;height:8px;background:url('http://i1.daumcdn.net/localimg/localimages/07/mapapidoc/vertex_white_small.png')}
.distanceInfo {position:relative;top:5px;left:5px;list-style:none;margin:0;}
.distanceInfo .label {display:inline-block;width:50px;}
.distanceInfo:after {content:none;}
</style>
<div class="row">
    <div class="col-md-12 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Course Form</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <form class="form-horizontal form-label-left" novalidate method="post" action="/console/play/action">
                <input type="hidden" name="mode" value="<?=(isset($row)) ? 'edit' : 'regist'?>"/>
                <input type="hidden" name="id" value="<?=(isset($row)) ? $row->id : ''?>"/>
               
                    <div>
                        Search : <input type="text" id="keyword" name="keyword" /> <a href="javascript:goMap();" class="btn btn-xs btn-info">GO MAP</a>
                    </div>
                    <div id="map" style="height:400px;" class="item form-group">
                        Map Loading...
                    </div>
                    
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="hidden" id="st_pt" name="st_pt" required="required" class="form-control col-md-7 col-xs-12" value="<?=(isset($row)) ? $row->st_pt : ''?>"/>
                            <input type="hidden" id="end_pt" name="end_pt" required="required" class="form-control col-md-7 col-xs-12" value="<?=(isset($row)) ? $row->end_pt : ''?>"/>
                            <input type="hidden" id="way_pt" name="way_pt" required="required" class="form-control col-md-7 col-xs-12" value="<?=(isset($row)) ? str_replace('GEOMETRYCOLLECTION(', '', substr($row->way_pt, 0, strlen($row->way_pt)-1)) : ''?>"/>
                            <input type="hidden" id="distance" name="distance" required="required" class="form-control col-md-7 col-xs-12" value="<?=(isset($row)) ? $row->distance : ''?>"/>
                            <input type="hidden" id="walk_time" name="walk_time" required="required" class="form-control col-md-7 col-xs-12" value="<?=(isset($row)) ? $row->walk_time : ''?>"/>    
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
<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=8fa982632b10ed739e3b21f74732b435&libraries=services"></script>
<script src="/assets/libs/validator/validator.js"></script>
<script>
    var mapContainer = document.getElementById('map'), // 지도를 표시할 div  
        mapOption = { 
            center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
            level: 3 // 지도의 확대 레벨
        };

    var map = new daum.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

    var drawingFlag = false; // 선이 그려지고 있는 상태를 가지고 있을 변수입니다
    var moveLine; // 선이 그려지고 있을때 마우스 움직임에 따라 그려질 선 객체 입니다
    var clickLine // 마우스로 클릭한 좌표로 그려질 선 객체입니다
    var distanceOverlay; // 선의 거리정보를 표시할 커스텀오버레이 입니다
    var dots = {}; // 선이 그려지고 있을때 클릭할 때마다 클릭 지점과 거리를 표시하는 커스텀 오버레이 배열입니다.
    

    // 지도에 클릭 이벤트를 등록합니다
    // 지도를 클릭하면 선 그리기가 시작됩니다 그려진 선이 있으면 지우고 다시 그립니다
    daum.maps.event.addListener(map, 'click', function(mouseEvent) {

        // 마우스로 클릭한 위치입니다 
        var clickPosition = mouseEvent.latLng;

        // 지도 클릭이벤트가 발생했는데 선을 그리고있는 상태가 아니면
        if (!drawingFlag) {

            deleteMapData();

            // 상태를 true로, 선이 그리고있는 상태로 변경합니다
            drawingFlag = true;
            
            // 지도 위에 선이 표시되고 있다면 지도에서 제거합니다
            deleteClickLine();
            
            // 지도 위에 커스텀오버레이가 표시되고 있다면 지도에서 제거합니다
            deleteDistnce();

            // 지도 위에 선을 그리기 위해 클릭한 지점과 해당 지점의 거리정보가 표시되고 있다면 지도에서 제거합니다
            deleteCircleDot();
        
            // 클릭한 위치를 기준으로 선을 생성하고 지도위에 표시합니다
            clickLine = new daum.maps.Polyline({
                map: map, // 선을 표시할 지도입니다 
                path: [clickPosition], // 선을 구성하는 좌표 배열입니다 클릭한 위치를 넣어줍니다
                strokeWeight: 4, // 선의 두께입니다 
                strokeColor: '#db4040', // 선의 색깔입니다
                strokeOpacity: 1, // 선의 불투명도입니다 0에서 1 사이값이며 0에 가까울수록 투명합니다
                strokeStyle: 'solid' // 선의 스타일입니다
            });
            
            // 선이 그려지고 있을 때 마우스 움직임에 따라 선이 그려질 위치를 표시할 선을 생성합니다
            moveLine = new daum.maps.Polyline({
                strokeWeight: 4, // 선의 두께입니다 
                strokeColor: '#db4040', // 선의 색깔입니다
                strokeOpacity: 0.5, // 선의 불투명도입니다 0에서 1 사이값이며 0에 가까울수록 투명합니다
                strokeStyle: 'solid' // 선의 스타일입니다    
            });
        
            // 클릭한 지점에 대한 정보를 지도에 표시합니다
            displayCircleDot(clickPosition, 0, true);

                
        } else { // 선이 그려지고 있는 상태이면

            // 그려지고 있는 선의 좌표 배열을 얻어옵니다
            var path = clickLine.getPath();

            // 좌표 배열에 클릭한 위치를 추가합니다
            path.push(clickPosition);
            
            // 다시 선에 좌표 배열을 설정하여 클릭 위치까지 선을 그리도록 설정합니다
            clickLine.setPath(path);

            var distance = Math.round(clickLine.getLength());
            displayCircleDot(clickPosition, distance, true);
        }
    });
        
    // 지도에 마우스무브 이벤트를 등록합니다
    // 선을 그리고있는 상태에서 마우스무브 이벤트가 발생하면 그려질 선의 위치를 동적으로 보여주도록 합니다
    daum.maps.event.addListener(map, 'mousemove', function (mouseEvent) {

        // 지도 마우스무브 이벤트가 발생했는데 선을 그리고있는 상태이면
        if (drawingFlag){
            
            // 마우스 커서의 현재 위치를 얻어옵니다 
            var mousePosition = mouseEvent.latLng; 

            // 마우스 클릭으로 그려진 선의 좌표 배열을 얻어옵니다
            var path = clickLine.getPath();
            
            // 마우스 클릭으로 그려진 마지막 좌표와 마우스 커서 위치의 좌표로 선을 표시합니다
            var movepath = [path[path.length-1], mousePosition];
            moveLine.setPath(movepath);    
            moveLine.setMap(map);
            
            var distance = Math.round(clickLine.getLength() + moveLine.getLength()), // 선의 총 거리를 계산합니다
                content = '<div class="dotOverlay distanceInfo">총거리 <span class="number">' + distance + '</span>m</div>'; // 커스텀오버레이에 추가될 내용입니다
            
            $('#distance').val(distance);
            // 거리정보를 지도에 표시합니다
            showDistance(content, mousePosition);   
        }             
    });                 

    // 지도에 마우스 오른쪽 클릭 이벤트를 등록합니다
    // 선을 그리고있는 상태에서 마우스 오른쪽 클릭 이벤트가 발생하면 선 그리기를 종료합니다
    daum.maps.event.addListener(map, 'rightclick', function (mouseEvent) {

        // 지도 오른쪽 클릭 이벤트가 발생했는데 선을 그리고있는 상태이면
        if (drawingFlag) {
            
            // 마우스무브로 그려진 선은 지도에서 제거합니다
            moveLine.setMap(null);
            moveLine = null;  
            
            // 마우스 클릭으로 그린 선의 좌표 배열을 얻어옵니다
            var path = clickLine.getPath();
        
            // 선을 구성하는 좌표의 개수가 2개 이상이면
            if (path.length > 0) {

                // 마지막 클릭 지점에 대한 거리 정보 커스텀 오버레이를 지웁니다
                if (dots[dots.length-1].distance) {
                    dots[dots.length-1].distance.setMap(null);
                    dots[dots.length-1].distance = null;    
                }

                var distance = Math.round(clickLine.getLength());
                //, // 선의 총 거리를 계산합니다
                    //content = getTimeHTML(distance); // 커스텀오버레이에 추가될 내용입니다
                
                var walkkTime = distance / 67 | 0;
                $('#walk_time').val(walkkTime);
                    
                // 그려진 선의 거리정보를 지도에 표시합니다
                //showDistance(content, path[path.length-1]);  
                
                var position = dots[dots.length-1].circle.getPosition();
                var contents = '<span class="dot_large"></span>';
                
                // 클릭 지점을 표시할 빨간 동그라미 커스텀오버레이를 생성합니다
                var circleOverlay = new daum.maps.CustomOverlay({
                    content: contents,
                    position: position,
                    zIndex: 1
                });

                // 지도에 표시합니다
                circleOverlay.setMap(map);

                // 배열에 추가합니다
                dots[dots.length-1].circle.setMap(null);
                dots[dots.length-1].circle = circleOverlay;

                endPosition(position);

                deleteDistnce();
                 
            } else {

                // 선을 구성하는 좌표의 개수가 1개 이하이면 
                // 지도에 표시되고 있는 선과 정보들을 지도에서 제거합니다.
                deleteClickLine();
                deleteCircleDot(); 
                deleteDistnce();
                deleteMapData();
            }
            
            // 상태를 false로, 그리지 않고 있는 상태로 변경합니다
            drawingFlag = false;          
        }  
    });  

    function deleteMapData() {
        $('#st_pt').val('');
        $('#way_pt').val('');
        $('#end_pt').val('');
        $('#walk_time').val('');
        $('#distance').val('');
    }  

    // 클릭으로 그려진 선을 지도에서 제거하는 함수입니다
    function deleteClickLine() {
        if (clickLine) {
            clickLine.setMap(null);    
            clickLine = null;        
        }
    }

    // 마우스 드래그로 그려지고 있는 선의 총거리 정보를 표시하거
    // 마우스 오른쪽 클릭으로 선 그리가 종료됐을 때 선의 정보를 표시하는 커스텀 오버레이를 생성하고 지도에 표시하는 함수입니다
    function showDistance(content, position) {
        
        if (distanceOverlay) { // 커스텀오버레이가 생성된 상태이면
            
            // 커스텀 오버레이의 위치와 표시할 내용을 설정합니다
            distanceOverlay.setPosition(position);
            distanceOverlay.setContent(content);
            
        } else { // 커스텀 오버레이가 생성되지 않은 상태이면
            
            // 커스텀 오버레이를 생성하고 지도에 표시합니다
            distanceOverlay = new daum.maps.CustomOverlay({
                map: map, // 커스텀오버레이를 표시할 지도입니다
                content: content,  // 커스텀오버레이에 표시할 내용입니다
                position: position, // 커스텀오버레이를 표시할 위치입니다.
                xAnchor: 0,
                yAnchor: 0,
                zIndex: 3  
            });      
        }
    }

    // 그려지고 있는 선의 총거리 정보와 
    // 선 그리가 종료됐을 때 선의 정보를 표시하는 커스텀 오버레이를 삭제하는 함수입니다
    function deleteDistnce () {
        if (distanceOverlay) {
            distanceOverlay.setMap(null);
            distanceOverlay = null;
        }
    }

    // 선이 그려지고 있는 상태일 때 지도를 클릭하면 호출하여 
    // 클릭 지점에 대한 정보 (동그라미와 클릭 지점까지의 총거리)를 표출하는 함수입니다
    function displayCircleDot(position, distance, input) {

        var contents = '<span class="dot_large"></span>';
        if(distance == 0 || distance == -1) {
            contents = '<span class="dot_large"></span>';
            if(input) {
                if(distance == 0)
                    startPosition(position);
                else
                    endPosition(position);
            }
        } else {
            contents = '<span class="dot"></span>';
            if(input)
                wayPosition(position);
        }
        // 클릭 지점을 표시할 빨간 동그라미 커스텀오버레이를 생성합니다
        var circleOverlay = new daum.maps.CustomOverlay({
            content: contents,
            position: position,
            zIndex: 1
        });

        // 지도에 표시합니다
        circleOverlay.setMap(map);

        // 배열에 추가합니다
        dots.push({circle:circleOverlay, distance: distanceOverlay});
    }

    // 클릭 지점에 대한 정보 (동그라미와 클릭 지점까지의 총거리)를 지도에서 모두 제거하는 함수입니다
    function deleteCircleDot() {
        var i;

        for ( i = 0; i < dots.length; i++ ){
            if (dots[i].circle) { 
                dots[i].circle.setMap(null);
            }

            if (dots[i].distance) {
                dots[i].distance.setMap(null);
            }
        }

        dots = [];
    }

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


    function startPosition(latLng) {
        $('#st_pt').val('POINT' + latLng.toString().replace(',', ''));
    }

    function wayPosition(latLng) {
        if($('#way_pt').val() == '')
            $('#way_pt').val('POINT' + latLng.toString().replace(',', ''));
        else
            $('#way_pt').val($('#way_pt').val() + ',' + 'POINT' + latLng.toString().replace(',', ''));
    }

    function endPosition(latLng) {

        var way = $('#way_pt').val().split(',');

        $('#way_pt').val('');
        for(var i = 0; i < way.length-1; i++) {
            if($('#way_pt').val() == '')
                $('#way_pt').val(way[i]);
            else
                $('#way_pt').val($('#way_pt').val() + ',' + way[i]);
        }

        $('#end_pt').val('POINT' + latLng.toString().replace(',', ''));
    }


    function courseView() {

        deleteCircleDot();

        if($('#st_pt').val() != '') {

            var spt = $('#st_pt').val().replace('POINT(', '');
                spt = spt.replace(')', '');
            
            var temp = spt.split(' ');
            var latLng = new daum.maps.LatLng(temp[0], temp[1]);

            map.setCenter(latLng);

            displayCircleDot(latLng, 0, false);

            clickLine = new daum.maps.Polyline({
                map: map, // 선을 표시할 지도입니다 
                path: [latLng], // 선을 구성하는 좌표 배열입니다 클릭한 위치를 넣어줍니다
                strokeWeight: 4, // 선의 두께입니다 
                strokeColor: '#db4040', // 선의 색깔입니다
                strokeOpacity: 1, // 선의 불투명도입니다 0에서 1 사이값이며 0에 가까울수록 투명합니다
                strokeStyle: 'solid' // 선의 스타일입니다
            });
        }

        if($('#way_pt').val() != '') {
            var wpt = $('#way_pt').val().replace(/POINT/g, '');
            var pts = wpt.split(',');
            $.each(pts, function() {

                var pt = this.replace(',', ' ');
                    pt = pt.replace('(', '');
                        pt = pt.replace(')', '');
                var temp = pt.split(' ');
                var latLng = new daum.maps.LatLng(temp[0], temp[1]);

                // 그려지고 있는 선의 좌표 배열을 얻어옵니다
                var path = clickLine.getPath();

                // 좌표 배열에 클릭한 위치를 추가합니다
                path.push(latLng);
                
                // 다시 선에 좌표 배열을 설정하여 클릭 위치까지 선을 그리도록 설정합니다
                clickLine.setPath(path);

                var distance = Math.round(clickLine.getLength());
                displayCircleDot(latLng, distance, false);
            });
        }

        if($('#end_pt').val() != '') {
            var ept = $('#end_pt').val().replace('POINT(', '');
                ept = ept.replace(')', '');
            
            var temp = ept.split(' ');

            var latLng = new daum.maps.LatLng(temp[0], temp[1]);

            // 그려지고 있는 선의 좌표 배열을 얻어옵니다
            var path = clickLine.getPath();

            // 좌표 배열에 클릭한 위치를 추가합니다
            path.push(latLng);
            
            // 다시 선에 좌표 배열을 설정하여 클
            clickLine.setPath(path);

            displayCircleDot(latLng, -1, false);
        }

    }

    function goMap() {
        var str = $('#keyword').val();
        if(str != '') {
            // 장소 검색 객체를 생성합니다
            var ps = new daum.maps.services.Places(); 

            // 키워드로 장소를 검색합니다
            ps.keywordSearch(str, placesSearch);
        }
    }

    // 키워드 검색 완료 시 호출되는 콜백함수 입니다
    function placesSearch (status, data, pagination) {
        if (status === daum.maps.services.Status.OK) {

            // 검색된 장소 위치를 기준으로 지도 범위를 재설정하기위해
            // LatLngBounds 객체에 좌표를 추가합니다
            var bounds = new daum.maps.LatLngBounds();    

             for (var i = 0; i < data.places.length; i++) {

                bounds.extend(new daum.maps.LatLng(data.places[i].latitude, data.places[i].longitude));
            }       


            // 검색된 장소 위치를 기준으로 지도 범위를 재설정합니다
            map.setBounds(bounds);
        } 
    }

    $('#keyword').keydown(function(e){
        if(e.keyCode == 13) {
            goMap();
            return false;
        }
    });

    courseView();
</script>
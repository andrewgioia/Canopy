<h2 class="float-left home">
    <?php echo $data[ 'chart' ][ 'title' ]; ?>
</h2>

<menu class="controls">
    <a href="javascript:;" id="toggleWeather" class="square icon button on">
        <i class="fa fa-sun-o"></i>
    </a>
    <a href="javascript:;" id="toggleWeekends" class="square button on">
        <span>Su</span>
    </a>
    <a href="javascript:;" id="toggleVacation" class="square icon button on right-pad">
        <i class="fa fa-plane" style="top:1px;position:relative"></i>
    </a>

    <a href="javascript:;" id="displayYear" data-display="year" class="toggle button left">Year</a>
    <a href="javascript:;" id="displayMonth" data-display="month" class="toggle button inner on">Month</a>
    <a href="javascript:;" id="displayDay" data-display="day" class="toggle button right">Day</a>
    <input type="text" size="15" class="control year picker hide" 
        value="<?php echo date( 'Y', mktime(0,0,0,$data['m'],$data['d'],$data['y']) );?>" />
    <input type="text" size="15" class="control month picker" 
        value="<?php echo date( 'F Y', mktime(0,0,0,$data['m'],$data['d'],$data['y']) );?>" />
    <input type="text" id="" size="15" class="control day picker hide"
        value="<?php echo date( 'F d, Y', mktime(0,0,0,$data['m'],$data['d'],$data['y']) );?>" />
    <a href="javascript:;" id="changeDisplay" class="square icon button right-pad">
        <i class="fa fa-filter"></i>
    </a>
    <a href="javascript:;" id="displayPrev" class="input-link">
        <i class="fa fa-chevron-left"></i>
    </a>
    <a href="javascript:;" id="displayNext" class="input-link">
        <i class="fa fa-chevron-right"></i>
    </a>

    <a href="javascript:;" id="exportChart" class="square icon button dropdown-launch">
        <i class="fa fa-cog"></i>
        <span id="exportChartMenu" class="dropdown hide">
            <span class="png action">
                <i class="fa fa-circle blue-color"></i> 
                &nbsp;Export as PNG image
            </span>
            <span class="svg action">
                <i class="fa fa-circle teal-color"></i> 
                &nbsp;Export as SVG vector
            </span>
            <span class="pdf action">
                <i class="fa fa-circle green-color"></i> 
                &nbsp;Export as PDF file
            </span>
        </span>
    </a>
</menu>

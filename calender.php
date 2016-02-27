<?php

class Calendar {  
     
    public function __construct(){     
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }
     
    private $dbConn=null;
    private $currentYear=0;
    private $currentMonth=0;
    private $currentDay=0;
    private $currentDate=null;
    private $daysInMonth=0;
    private $naviHref= null;
    private $year= null;
    private $month= null;
     
    public function renderCalender() {
		$year = '';
        $year = $_GET['year'];
		$month = $_GET['month'];
        if($year==null){
			$year = date("Y",time());  
        }
		if(null==$month){
			$month = date("m",time());
		}

        $this->currentYear=$year;
        $this->currentMonth=$month;
        $this->daysInMonth=date('t',strtotime($year.'-'.$month.'-01'));  
		$weeksInMonth = intval($this->daysInMonth/7);
		if($this->daysInMonth%7!=0)
		{
			$weeksInMonth++;
		}
		$monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$this->daysInMonth));
		$monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
		if($monthEndingDay<$monthStartDay){
			$weeksInMonth++;
		}
		
        $content='<div id="calendarDiv" align="left">'.
                        '<div class="box">'.
                        $this->renderHeader().
                        '</div>'.
                        '<div style="border:1px solid #787878 ;">'.
							'<ul class="label"><li>Mon</li><li>Tues</li><li>Wed</li><li>Thu</li><li>Fri</li><li>Sat</li><li>Sun</li></ul>'.
							'<ul class="dates">';

					for( $i=0; $i<$weeksInMonth; $i++ ){
						$content.= "<br/><div>";
						for($j=1;$j<=7;$j++){
							$content.=$this->renderCell($i*7+$j);
						}
						$content.= "</div>";
					}
		$content.='</ul></div></div>';
        return $content;   
    }
     
	public function saveDateEvent(){
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $time = $_POST['time'];
        $date = $_POST['date'];
		
		$servername = "mysql13.000webhost.com";
		$username = "a5254011_root";
		$password = "password@1";
		$dbname = "a5254011_phpDB";
		$eventId = 0;
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "select max(eventId) from calendarEvents";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			if($row = $result->fetch_assoc()) {
				$eventId=intval($row["max(eventId)"])+1;
			}
		}
		$sql = "insert into calendarEvents values(".$eventId.",'".$title."','".$desc."','".$date." ".$time."')";
		$result = $conn->query($sql);

	}
     
	public function getDateEvents(){
        $date = $_POST['date'];

		$content='{"data":[';
		$servername = "mysql13.000webhost.com";
		$username = "a5254011_root";
		$password = "p2ssword-1";
		$dbname = "a5254011_phpDB";
		$flag=0;
		$conn = new mysqli($servername, $username, $password, $dbname);
		$sql = "SELECT eventId, title,description,eventdate from calendarEvents where eventdate between '".$date."' and '".$date." 23:59:59' order by eventdate";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if($flag==1)
				{
					$content.=',';
				}
				$content.='{"eventId":"'.$row["eventId"].'","title":"'.$row["title"].'","description":"'.$row["description"].'","time":"'.$row["eventdate"].'"}';
				$flag=1;
			}
		} else {
		}
		$content.=']}';
		return $content;
	}
	
	public function updateEvent()
	{
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $time = $_POST['time'];
        $eventId = $_POST['eventId'];
		
		$servername = "mysql13.000webhost.com";
		$username = "a5254011_root";
		$password = "p2ssword-1";
		$dbname = "a5254011_phpDB";

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "update calendarEvents set title='".$title."',description='".$desc."',eventdate='".$time."' where eventId=".$eventId."";
		$result = $conn->query($sql);

	}
	
    private function renderCell($cellNumber){
         
        if($this->currentDay==0){
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                $this->currentDay=1;
            }
        }
         
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
            $cellContent = $this->currentDay;
            $this->currentDay++;   
        }else{
            $this->currentDate =null;
            $cellContent=null;
        }
         
        return '<span id="li-'.$this->currentDate.'" onclick=showEvents("'.$this->currentDate.'")>'.
					'<div>'.$cellContent.'</div>'.
				'</span>';
    }
	
    private function renderHeader(){
         
        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
        return
            '<div class="header">'.
                '<a style="left:0px;" class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Prev</a>'.
                    '<span class="title">'.date('Y M',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
                '<a style="right:0px" class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
            '</div>';
    }
         
}
?>
<?php

class PiaReportItem extends PiaBase {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'report_item';
  	protected $guarded = array('ri_id', 'r_id');
	protected $primaryKey = 'ri_id';

	public $status_list = ['表單待填','主管否決','組長否決','代填','待主管簽署','待組長簽署','完成'];

	public function is_finished()
	{
		return $this->ri_status == "完成";
	}

	public function is_editable()
	{
		return array_flip($this->status_list)[$this->ri_status] < 3;
	}

	public function save(array $options = array()){
		if(!isset($this->ri_status))
			$this->ri_status = '表單待填';

		switch($this->ri_status){
		case "表單待填":
			$data_to_validate = [
				// "ri_id" => $this->ri_id,
				"r_id" => $this->r_id,
	    		"ri_base" => $this->ri_base,
	    		"ri_discover" => $this->ri_discover,
	    		"ri_recommand" => $this->ri_recommand,
				"ri_status" => $this->ri_status,
			];
			$rule = [
				// "ri_id" => 'required',
				// "r_id" => 'required',
				"ri_base" => 'required',
				"ri_status" => 'required',
			];
			break;
		case "代填":
			$data_to_validate = [
				// "ri_id" => $this->ri_id,
				"r_id" => $this->r_id,
	    		"ri_base" => $this->ri_base,
	    		"ri_discover" => $this->ri_discover,
	    		"ri_recommand" => $this->ri_recommand,
				"ri_status" => $this->ri_status,
				"handler_name" => $this->handler_name,
				"handler_email" => $this->handler_email,
			];
			$rule = [
				// "ri_id" => 'required',
				"r_id" => 'required',
				"ri_base" => 'required',
				"ri_status" => 'required',
				"handler_name" => 'required',
				"handler_email" => 'required|email',
			];
			break;
		case '待主管簽署':
		case '待組長簽署':
			$data_to_validate = [
				// "ri_id" => $this->ri_id,
				"r_id" => $this->r_id,
				"ri_base" => $this->ri_base,
				"ri_discover" => $this->ri_discover,
				"ri_recommand" => $this->ri_recommand,
				"ri_status" => $this->ri_status,
				"handler_name" => $this->handler_name,
				"handler_email" => $this->handler_email,
				'analysis' => $this->analysis,
				'rectify_measure' => $this->rectify_measure,
				'rec_finish_date' => $this->rec_finish_date,
				'precautionary_measure' => $this->precautionary_measure,
				'pre_finish_date' => $this->pre_finish_date,
				'es_id' => $this->es_id,
				'fill_date' => $this->fill_date,
			];
			$rule = [
				// "ri_id" => 'required',
				"r_id" => 'required',
				"ri_base" => 'required',
				"ri_status" => 'required',
				"handler_name" => 'required',
				"handler_email" => 'required|email',
				'analysis' => 'required',
				'rectify_measure' => 'required',
				'rec_finish_date' => 'required',
				'precautionary_measure' => 'required',
				'pre_finish_date' => 'required',
				// 'es_id' => 'required',
				'fill_date' => 'required',
			];
			break;
		case '完成':
			$data_to_validate = [
				// "ri_id" => $this->ri_id,
				"r_id" => $this->r_id,
				"ri_base" => $this->ri_base,
				"ri_discover" => $this->ri_discover,
				"ri_recommand" => $this->ri_recommand,
				"ri_status" => $this->ri_status,
				"handler_name" => $this->handler_name,
				"handler_email" => $this->handler_email,
				'analysis' => $this->analysis,
				'rectify_measure' => $this->rectify_measure,
				'rec_finish_date' => $this->rec_finish_date,
				'precautionary_measure' => $this->precautionary_measure,
				'pre_finish_date' => $this->pre_finish_date,
				// 'es_id' => $this->es_id,
				'fill_date' => $this->fill_date,
				'confirm_timestamp1' => $this->confirm_timestamp1,
				'confirm_timestamp2' => $this->confirm_timestamp2,
			];
			$rule = [
				// "ri_id" => 'required',
				"r_id" => 'required',
				"ri_base" => 'required',
				"ri_status" => 'required',
				"handler_name" => 'required',
				"handler_email" => 'required|email',
				'analysis' => 'required',
				'rectify_measure' => 'required',
				'rec_finish_date' => 'required',
				'precautionary_measure' => 'required',
				'pre_finish_date' => 'required',
				// 'es_id' => 'required',
				'fill_date' => 'required',
				'confirm_timestamp1' => 'required',
				'confirm_timestamp2' => 'required',
			];
			break;
		default:
			throw new Exception("Invalid status!");
		}
		$validator = Validator::make($data_to_validate,$rule);
		if($validator->fails())
			throw new Exception($validator->messages());
		parent::save($options);
	}

	public function get_base_str(){
		$src = Config::get('pia_report');
		foreach ($src as $key => $value) {
			if($value->value == $this->ri_base)
				return $value->text;
		}
	}

	public function report(){
		return $this->hasOne('PiaReport','r_id','r_id');
	}

	public function get_number(){
		return self::where("r_id","=",$this->r_id)->where("ri_id","<=",$this->ri_id)->count();
	}

	public function get_serial(){
		return (date('Y') - 1911) . "-" . date("m-d") . "-" . ($this->ri_id % 1000);
	}

	protected function get_paper_path(){
		return storage_path("report_item_pdf/" . $this->ri_id );
	}

	public function get_paper(){
		$full_pdf_path = $this->get_paper_path() . ".pdf";
		if(file_exists($full_pdf_path))
			return $full_pdf_path;
		else
			return $this->gen_paper();
	}

	public function gen_html($hide_sign = false){
		$report = $this->report()->first();
		$audit = $report->audit()->first();
		$auditor = $audit->person()->first();
		$dept = $audit->dept()->first();

		if($hide_sign)
			return View::make('macro/report_item')->with([
				"item" => $this,
				"report" => $report,
				"audit" => $audit,
				"auditor" => $auditor,
				"dept" => $dept,
				"raise_depart" => PiaGlobal::grab('pia_team_name'),
				"hide_sign" => $hide_sign,
			])->render();
		else
			return View::make('macro/report_item')->with([
				"item" => $this,
				"report" => $report,
				"audit" => $audit,
				"auditor" => $auditor,
				"dept" => $dept,
				"raise_depart" => PiaGlobal::grab('pia_team_name'),
			])->render();
	}

	public function gen_paper($hide_sign = false){
		$pdf_path = $this->get_paper_path();
		$full_pdf_path = $pdf_path . ".pdf";
		if(file_exists($full_pdf_path))
			unlink($full_pdf_path);
		PDF::html('paper', ['content' => $this->gen_html($hide_sign), 'title' => "個人資料管理制度矯正預防處理單"], $pdf_path);
		return $full_pdf_path;
	}
}

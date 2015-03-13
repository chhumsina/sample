<?php

class RangeApprovalStaffPrivilege extends \Eloquent {
	protected $fillable = array();
	
	public static function  getCollectionApprovalRange($staffId) {
		
		$approvalRangeCollection = array();
						
		$approvalRangeCollection['KHR'] = RangeApprovalStaffPrivilege::getApprovalRangeByCurrencyAndStaff('KHR',$staffId);
		$approvalRangeCollection['USD'] = RangeApprovalStaffPrivilege::getApprovalRangeByCurrencyAndStaff('USD',$staffId);
		$approvalRangeCollection['THB'] = RangeApprovalStaffPrivilege::getApprovalRangeByCurrencyAndStaff('THB',$staffId);
		$approvalRangeCollection['VND'] = RangeApprovalStaffPrivilege::getApprovalRangeByCurrencyAndStaff('VND',$staffId);
		
		return $approvalRangeCollection;
	}
	
	public static function  getApprovalRangeByCurrencyAndStaff($currencyId,$staffId) {
		
		$approvalRangeStaffPrivileges = DB::table('sys_range_approval as ra')
						->join('sys_range_approval_staff_privilege as rasp','ra.range_approval_id','=','rasp.range_approval__id')
						->where('ra.currency__id',$currencyId)
						->where('rasp.privilege_to_staff_id',$staffId)
						->orderBy('sequence_number','asc')
						->get();
						
		return $approvalRangeStaffPrivileges;
	}
}
<?php namespace

App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MResult;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller {

	/**
	 * @brief 上传文件
	 */
    public function uploadFile(Request $request, $type)
    {


        $file = $request->file('preview');
        if ($request->hasFile('preview')) {
//            $path = $request->preview->storeAs('/upload/images', time().'.'.$file->getClientOriginalExtension());
        }
        $file->store(1);
        $path = Storage::put('avatars/1', $request->preview);
        Storage::move($path, public_path().'images/.'.time().mt_rand(0, 9999999999).$file->getCLientOriginalExtension());
        $m_result = new MResult();
        $m_result->status = 0;
        $m_result->message = "上传成功";
        $m_result->uri = $path;
		return $m_result->toJson();
	 }
}

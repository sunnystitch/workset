<?php
/**
 * created by liuanyuan
 * desc:
 * date: 2020/6/2
 * time: 4:50 下午
 */


/**
 * laravel 封装的上传图片的方法 加上public
 * @param Request $request
 * @return mixed
 */
function upload(Request $request)
{
    $type = $request->get( 'upload_type ',1);//0 - 七牛云，1-网络
    $file = $request->file('file');//获取上传文件信息

    if ($file->isValid()) {
        $title =  $file ->getClientOriginalName();
        $filename = md5(time() .$title). '.'. $file->getClientOriginalExtension();
        $size =  $file->getSize();
        if ($type)  {
            $path = storage_path('app/public/uploads/images');
            $res = $file->move($path,$filename);//上传到本地
            $address = env('APP_URL').'storage/uploads/images/'.$filename;
        } else {

            $res = Storage::disk('qiniu')->put ( $filename, fopen($file->getRealPath(),'r'));

            $address = config('filesyst(xxx)ems.disks.qiniu.domain').'/' .$filename ;
        }
        if ($res) {
            $result['code'] = 200;
            $result['msg'] = '上传成功';
            $result['data'] = [
                'old_title' => $title,
                'title' => $filename,
                'size' => $size,
                'addr'=> $address
            ];
        } else {
            $result['code'] = 500;
            $result['msg'] = '上传失败';
            $result['data'] = $res;
        }

    } else {
        $result['code'] = 500;
        $result['msg'] = '上传的文件无效';
        $result['data'] = '';
    }

    return $this->response->array($result);
}
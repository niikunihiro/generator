# 置換機能付きのファイル生成スクリプト（仮称）

## 使い方

```
require_once __DIR__ . '/vendor/autoload.php';

$data = [
    [':ClassName:' => 'My_job', ':job_id:' => 'JIRO0001', ':Service:' => 'MyService'],
    [':ClassName:' => 'Your_job', ':job_id:' => 'JIRO0002', ':Service:' => 'MyService'],
    [':ClassName:' => 'His_job', ':job_id:' => 'JIRO0003', ':Service:' => 'MyService'],
    [':ClassName:' => 'Her_job', ':job_id:' => 'JIRO0004', ':Service:' => 'MyService'],
    [':ClassName:' => 'Our_job', ':job_id:' => 'JIRO0004', ':Service:' => 'MyService'],
];

foreach ($data as $item) {
    $filename = __DIR__ . sprintf('/files/%s.php', $item[':ClassName:']);
    if (file_exists($filename)) {
        continue;
    }
    $Generator = new Generator\Generator(new SplFileObject($filename, 'w'));

    $contents = file_get_contents(__DIR__ . '/files/template/Job.txt');
    $Generator->setContents($contents);

    $Generator->replace($item);
    $Generator->fire();
}
```
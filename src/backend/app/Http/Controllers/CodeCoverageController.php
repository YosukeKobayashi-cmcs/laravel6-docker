<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector as DriverSelector;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as HtmlReport;
use SebastianBergmann\CodeCoverage\RawCodeCoverageData;

class CodeCoverageController extends Controller
{

        /**
     * カバレッジデータ保存用テンポラリディレクトリ (デフォルト：TMP)
     */
    protected $tmp;

    /**
     * カバレッジ取得対象ディレクトリ (デフォルト：ROOT.DS.APP_DIR)
     */
    protected $tgt;
    /**
     * レポート出力対象 (デフォルト：WWW_ROOT.'report')
     */
    protected $dst;

    /**
     * ディレクトリ存在チェック、書き込みチェックエラーの場合、カバレッジ取得を行わない
     */
    protected $enable = true;

    /**
     * 環境変数から保存先を取得(なければcakephp2に適したデフォルト値)
     */
    private function initProperty() {
            $this->tmp = env('COVERAGE_TMP_DIR', '/var/www/html/app/Http');
            $this->tgt = env('COVERAGE_TGT_DIR', '/var/www/html/public/coverage/tmp');
            $this->dst = env('COVERAGE_DST_DIR', '/var/www/html/public/coverage/reports');

            // 存在チェック＋なければ作成(失敗したらカバレッジ取得しない)
            if (!file_exists($this->tmp) && !is_dir($this->tmp)) {
                if (!mkdir($this->tmp, 0755, true)) {
                    $this->enable = false;
                }
            }
            if (!file_exists($this->dst) && !is_dir($this->dst)) {
                if (!mkdir($this->dst, 0755, true)) {
                    $this->enable = false;
                }
            }
    }
    
    /**
     * create code coverage reports
     */
    private function createReport() {

        $coverages = glob($this->tmp.'/coverage-*.json');

        // CodeCoverage オブジェクトを正しく初期化
        $filter = new Filter();
        $filter->includeDirectory($this->tgt); // ここを変更
        $driver = (new DriverSelector())->forLineCoverage($filter);
        $codeCoverage = new CodeCoverage($driver, $filter);

        foreach ($coverages as $index => $coverageFile)
        {
            $codecoverageData = json_decode(file_get_contents($coverageFile), true);
            $rawCoverageData = RawCodeCoverageData::fromXdebugWithoutPathCoverage($codecoverageData);
            // ファイル名（テストケース名）を使用
            $testCaseName = basename($coverageFile, '.json');
            $codeCoverage->append($rawCoverageData, $testCaseName);
        }

        $report = new HtmlReport();
        $report->process($codeCoverage, $this->dst);
    }

    /**
     * delete coverage data
     */
    private function deleteCoverageData() {
        $coverages = glob($this->tmp.'/coverage-*.json');
        foreach ($coverages as $val ) {
            unlink($val); // delete file
        }
    }

    public function report(Request $request) {
        $this->initProperty();
        if ($this->enable === false) {
            echo 1;
            return;
        }
        // カバレッジレポート生成
        
        $this->createReport();
        return redirect('/coverage/reports');
    }

    public function delete(Request $request) {
        $this->initProperty();
        $this->deleteCoverageData();
        echo 'deleted coverage data';
    }
}

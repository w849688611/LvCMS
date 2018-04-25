<?php
namespace app\index\controller;

use app\admin\model\AdminModel;
use app\lib\exception\BaseException;
use app\service\TokenService;
use think\Controller;
use think\Exception;
use think\Request;
use think\View;

class Index extends Controller
{
    public function index(Request $request)
    {
        $str="The same thing happens to this day, though on a smaller scale, wherever a sediment-laden river or stream emerges from a mountain valley onto relatively flat land, dropping its load as the current slows: the water usually spreads out fanwise, depositing the sediment in the form of a smooth, fan-shaped slope. Sediments are also dropped where a river slows on entering a lake or the sea, the deposited sediments are on a lake floor or the seafloor at first, but will be located inland at some future date, when the sea level falls or the land rises; such beds are sometimes thousands of meters thick.

In lowland country almost any spot on the ground may overlie what was once the bed of a river that has since become buried by soil; if they are now below the water’s upper surface (the water table), the gravels and sands of the former riverbed, and its sandbars, will be saturated with groundwater.

So much for unconsolidated sediments. Consolidated (or cemented) sediments, too, contain millions of minute water-holding pores. This is because the gaps among the original grains are often not totally plugged with cementing chemicals; also, parts of the original grains may become dissolved by percolating groundwater, either while consolidation is taking place or at any time afterwards. The result is that sandstone, for example, can be as porous as the loose sand from which it was formed.";
        $words=array();
        if (preg_match_all('|(\w+)|',$str,$reg)) {
//            foreach ($reg[1] as $w){
//                if(!array_key_exists($w,$words)){
//                    $words[$w]=0;
//                }
//                $words[$w]++;
//            }
            $words = array_count_values(array_map("strtolower",$reg[0]));
        }
        arsort($words);
        foreach ($words as $w=>$n)
            echo "$w $n <br>\n";
    }

}

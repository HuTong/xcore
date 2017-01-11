<?php
namespace HuTong\Xcore;
/**
 * @desc 分页类
 */
class Pagination
{
	private $page_total;          //总记录数
    private $page_size;           //一页显示的记录数
    private $page_current;        //当前页
    private $page_count;     	  //总页数
    private $page_index;          //起头页数
    private $page_end;            //结尾页数
    private $page_url;            //获取当前的url
    /*
     * $show_pages
     * 页面显示的格式，显示链接的页数为2*$show_pages+1。
     * 如$show_pages=2那么页面上显示就是[首页] [上页] 1 2 3 4 5 [下页] [尾页]
     */
    private $show_pages= 2;
	private $first_lang= '首页';
	private $prev_lang = '上一页';
	private $next_lang = '下一页';
	private $last_lang = '尾页';
	private $page_remark_lang = "<p class='pageRemark'>共<b>%s</b>页<b>%s</b>条数据</p>";

	private $full_tag_open  = '<nav><ul class="pagination">';
	private $full_tag_close = '</ul></nav>';
	private $first_tag_open = '<li>';
	private $first_tag_close= '</li>';
	private $prev_tag_open  = '<li>';
	private $prev_tag_close = '</li>';
	private $next_tag_open  = '<li>';
	private $next_tag_close = '</li>';
	private $last_tag_open  = '<li>';
	private $last_tag_close = '</li>';
	private $curr_tag_open  = '<li>';
	private $curr_tag_close = '</li>';
	private $ellipsis_tag_open  = '<li>';
	private $ellipsis_tag_close = '</li>';


	public function __construct($config = array())
	{
		if($config)
		{
			if(isset($config['first_lang']))
			{
				$this->first_lang = $config['first_lang'];
			}
			if(isset($config['prev_lang']))
			{
				$this->prev_lang = $config['prev_lang'];
			}
			if(isset($config['next_lang']))
			{
				$this->next_lang = $config['next_lang'];
			}
			if(isset($config['last_lang']))
			{
				$this->last_lang = $config['last_lang'];
			}
			if(isset($config['page_remark_lang']))
			{
				$this->page_remark_lang = $config['page_remark_lang'];
			}
			if(isset($config['show_pages']))
			{
				$this->show_pages = $config['show_pages'];
			}

			$tags = array('full','first','prev','next','last','curr','ellipsis');

			foreach ($tags as $tag) {
				$tag_open  = $tag.'_tag_open';
				$tag_close = $tag.'_tag_close';

				if(isset($config[$tag_open]))
				{
					$this->$tag_open = $config[$tag_open];
				}

				if(isset($config[$tag_close]))
				{
					$this->$tag_close = $config[$tag_close];
				}
			}
		}
	}

    private function initPage() {
        if ($this->page_total < 0)
        {
			$this->page_total = 0;
		}

        if ($this->page_current < 1)
		{
			$this->page_current = 1;
		}

        if ($this->page_count < 1)
        {
			$this->page_count = 1;
		}

        if ($this->page_current > $this->page_count)
        {
			$this->page_current = $this->page_count;
		}

        $this->limit = ($this->page_current - 1) * $this->page_size;
        $this->page_index = $this->page_current - $this->show_pages;
        $this->page_end = $this->page_current + $this->show_pages;

        if ($this->page_index < 1)
		{
            $this->page_end = $this->page_end + (1 - $this->page_index);
            $this->page_index = 1;
        }

        if ($this->page_end > $this->page_count)
		{
            $this->page_index = $this->page_index - ($this->page_end - $this->page_count);
            $this->page_end = $this->page_count;
        }

        if ($this->page_index < 1)
        {
			$this->page_index = 1;
		}
    }

	/**
	 * @desc 输出分页
	 * @param  integer $page_total   [总记录数]
	 * @param  integer $page_size    [页显示的记录数]
	 * @param  integer $page_current [当前页]
	 * @param  [type]  $page_url     [获取当前的url]
	 * @param  integer $show_pages   [前后显示页数]
	 * @return [string]              [description]
	 */
    public function page_show($page_total = 1, $page_size = 1, $page_current = 1, $page_url, $show_pages = '')
	{
		if($show_pages)
		{
			$this->show_pages = $show_pages;
		}
		//设置配置信息
		$this->page_total = $this->numeric($page_total);
        $this->page_size = $this->numeric($page_size);
        $this->page_current = $this->numeric($page_current);
        $this->page_count = ceil($this->page_total / $this->page_size);
		$this->page_url = $page_url;

		$this->initPage();

        $str  = $this->full_tag_open;
        $str .= $this->page_home();
        $str .= $this->page_prev();
        if ($this->page_index > 1)
		{
            $str .= $this->ellipsis_tag_open."<span>...</span>".$this->ellipsis_tag_close;
        }

        for ($i = $this->page_index; $i <= $this->page_end; $i++)
		{
            if ($i == $this->page_current)
			{
				$str .= $this->curr_tag_open."<a href='javascript:;' class='curr'>$i</a>".$this->curr_tag_close;
            } else {
                $str .= $this->curr_tag_open."<a href='" . $this->page_replace($i) . "'>$i</a>".$this->curr_tag_close;
            }
        }

        if ($this->page_end < $this->page_count)
		{
            $str .= $this->ellipsis_tag_open."<span>...</span>".$this->ellipsis_tag_close;
        }

        $str .= $this->page_next();
        $str .= $this->page_last();
		$str .= sprintf($this->page_remark_lang, $this->page_count, $this->page_total);
        $str .= $this->full_tag_close;

        return $str;
    }

    //检测是否为数字
    private function numeric($num) {
        if (strlen($num))
		{
            if (!preg_match("/^[0-9]+$/", $num))
			{
                $num = 1;
            } else {
                $num = substr($num, 0, 11);
            }
        } else {
            $num = 1;
        }
        return $num;
    }

    //地址替换
    private function page_replace($page) {
        return str_replace("{page}", $page, $this->page_url);
    }

    //首页
    private function page_home() {
        if ($this->page_current != 1)
		{
            return $this->first_tag_open."<a href='" . $this->page_replace(1) . "'>".$this->first_lang."</a>".$this->first_tag_close;
        } else {
            return $this->first_tag_open."<span>".$this->first_lang."</span>".$this->first_tag_close;
        }
    }

    //上一页
    private function page_prev() {
        if ($this->page_current != 1)
		{
            return $this->curr_tag_open."<a href='" . $this->page_replace($this->page_current - 1) . "'>".$this->prev_lang."</a>".$this->curr_tag_close;
        } else {
            return $this->curr_tag_open."<span>".$this->prev_lang."</span>".$this->curr_tag_close;
        }
    }

    //下一页
    private function page_next() {
        if ($this->page_current != $this->page_count)
		{
            return $this->next_tag_open."<a href='" . $this->page_replace($this->page_current + 1) . "'>".$this->next_lang."</a>".$this->next_tag_close;
        } else {
            return $this->next_tag_open."<span>".$this->next_lang."</span>".$this->next_tag_close;
        }
    }

    //尾页
    private function page_last() {
        if ($this->page_current != $this->page_count)
		{
            return $this->last_tag_open."<a href='" . $this->page_replace($this->page_count) . "'>".$this->last_lang."</a>".$this->last_tag_close;
        } else {
            return $this->last_tag_open."<span>".$this->last_lang."</span>".$this->last_tag_close;
        }
    }
}

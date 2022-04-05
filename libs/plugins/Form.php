<?php

namespace REJ\Libs;

class Form {

    public $attribute = [];
    public $data = [];
    
    public function __construct( $return ) {
        $this->data = $return;
    }
    
    public function field( $attr ) {
        $validate = new Validate( $this->data );
        
        if($attr['type'] === 'text') {
            echo sprintf('<div class="form-group mb-2">
                <label>%s %s</label>
                <input name="%s" %s type="text" class="form-control input-sm %s %s" placeholder="%s" value="%s">
                %s
                </div>',
                $attr['label'],
                (($attr['require'] == 1) ? '<label class="text-danger" style="font-size:16px;">*</label>' : ''),
                $attr['name'],
                ((!empty($attr['attr'])) ? $attr['attr'] : ""),
                ((!empty($attr['class'])) ? $attr['class'] : ''),
                $validate->errorClass($attr['name']),
                $attr['placeholder'],
                $attr['value'],
                $validate->isError($attr['name'])
            );
        } else if($attr['type'] === 'textarea') {
            echo sprintf('<div class="form-group mb-2">
                <label>%s %s</label>
                <textarea name="%s" %s cols=%d rows=%d class="form-control input-sm %s %s" placeholder="%s">%s</textarea>
                %s
                </div>',
                $attr['label'],
                (($attr['require'] == 1) ? '<label class="text-danger" style="font-size:16px;">*</label>' : ''),
                $attr['name'],
                ((!empty($attr['attr'])) ? $attr['attr'] : ""),
                $attr['cols'],
                $attr['rows'],
                ((!empty($attr['class'])) ? $attr['class'] : ''),
                $validate->errorClass($attr['name']),
                $attr['placeholder'],
                $attr['value'],
                $validate->isError($attr['name'])
            );
        } else if($attr['type'] === 'number') {
            echo sprintf('<div class="form-group mb-2">
                <label>%s %s</label>
                <input name="%s" %s %s type="number" value="" class="form-control input-sm %s %s" placeholder="%s" value="%s">
                %s
                </div>',
                $attr['label'],
                (($attr['require'] == 1) ? '<label class="text-danger" style="font-size:16px;">*</label>' : ''),
                $attr['name'],
                ((!empty($attr['id'])) ? "id='".$attr['id']."'" : ""),
                ((!empty($attr['attr'])) ? $attr['attr'] : ""),
                ((!empty($attr['class'])) ? $attr['class'] : ''),
                $validate->errorClass($attr['name']),
                $attr['placeholder'],
                $attr['value'],
                $validate->isError($attr['name'])
            );
        } else if($attr['type'] === 'password') {
             echo sprintf('<div class="form-group mb-2">
                <label>%s %s</label>
                <input name="%s" %s type="password" class="form-control input-sm %s %s" placeholder="%s" value="%s">
                %s
                </div>',
                $attr['label'],
                (($attr['require'] == 1) ? '<label class="text-danger" style="font-size:16px;">*</label>' : ''),
                $attr['name'],
                ((!empty($attr['attr'])) ? $attr['attr'] : ""),
                ((!empty($attr['class'])) ? $attr['class'] : ''),
                $validate->errorClass($attr['name']),
                $attr['placeholder'],
                $attr['value'],
                $validate->isError($attr['name'])
            );
        } else if($attr['type'] == 'select') {
            echo sprintf('<div class="form-group mb-2">
                <label>%s %s</label>
                <select name="%s" %s class="form-control input-sm %s %s" style="margin-bottom:5px;">
                %s
                </select>
                %s
                </div>',
                $attr['label'],
                (($attr['require'] == 1) ? '<label class="text-danger" style="font-size:16px;">*</label>' : ''),
                $attr['name'],
                ((!empty($attr['attr'])) ? $attr['attr'] : ""),
                ((!empty($attr['class'])) ? $attr['class'] : ''),
                $validate->errorClass($attr['name']),
                $attr['option'],
                $validate->isError($attr['name'])
            );    
        }
    }
    
    public function button( $attr ) {
        echo sprintf('<button type="%s" name="%s" class="btn btn-%s btn-sm %s" %s>%s</button>',
            $attr['type'],
            $attr['name'],
            $attr['btn_class'],
             ((!empty($attr['class'])) ? $attr['class'] : ''),
            $attr['attr'],
            $attr['label']
        );
    }
    
    public function anchor( $attr ) {
        echo sprintf('<a href="%s" name="%s" class="btn btn-%s btn-sm %s">%s</a>',
            $attr['href'],
            $attr['name'],
            $attr['btn_class'],
            ((!empty($attr['class'])) ? $attr['class'] : ''),
            $attr['label']
        );
    }

    public function label( $attr ) {
        echo sprintf('<p class="mb-1"><label><strong>%s:</strong></label> %s</p>',
            $attr['label'],
            $attr['text']
        );
    }

    public function table( $attr ) {
        $head = "";
        $body = "";

        foreach($attr['header'] as $h) {
            $head .= "<th>$h</th>";
        }

        foreach($attr['body'] as $d) {
            $body .= "<tr>";
            foreach($d as $val) {
                $body .= "<td>$val</td>";
            }
            $body .= "</tr>";
        }

        echo sprintf('<div class="bg-secondary rounded-top text-gray-200 font-weight-bold border border-bottom-0 mb-0 p-1"><i class="fa fa-fw fa-list"></i> %s</div><table name="%s" class="table %s"><thead><tr>%s</tr><tbody>%s</tbody></table>',
            $attr['label'],
            $attr['name'],
            $attr['class'],
            $head,
            $body
        );
    }
}
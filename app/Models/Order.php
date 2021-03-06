<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //黑名单为空
    protected $guarded = [];
    protected $table = 'mini_order';

    public function desk()
    {
        return $this->belongsTo(Desk::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getProductsAttribute($products)
    {
        return array_values(json_decode($products, true) ?: []);
    }

    public function setProductsAttribute($products)
    {
        $this->attributes['products'] = json_encode(array_values($products));
    }


    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::saving(function ($model){
            $products = $model->products;

            foreach ($products as $key=>$product){
                $food=Food::find($product['id']);
                $products[$key]['name']=$food->name;
                $products[$key]['price']=$food->price;
                $products[$key]['total_price']=$food->price*$product['num'];
                $products[$key]['type']=$product['type'];

//                OrderFood::create([
//                    'order_id'=>$model->id,
//                    'desk_id'=>$model->desk_id,
//                    'food_id'=>$product['id'],
//                    'num'=>$product['num'],
//                    'price'=>$food->price,
//                    'total_price'=>$food->price*$product['num']
//                ]);
            }

            $model->products= $products;

        });
    }
}

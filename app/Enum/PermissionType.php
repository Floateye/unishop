<?php

namespace app\Enum;

enum PermissionType :string
{
    //========================
    // Dashboard Management
    //========================
    case DashboardView = 'dashboard:view';
   //========================
   // Products Management
   //========================

    case ProductList = 'product:list';
    case ProductView = 'product:view';
    case ProductCreate = 'product:create';
    case ProductEdit = 'product:edit';
    case ProductDelete = 'product:delete';


    //========================
    // Cart Management
    //========================

    case CartList = 'cart:list';
    case CartView = 'cart:view';
    case CartCreate = 'cart:create';
    case CartEdit = 'cart:edit';
    case CartDelete = 'cart:delete';


    //========================
    // Order Management
    //========================

    case OrderList = 'order:list';
    case OrderView = 'order:view';
    case OrderCreate = 'order:create';
    case OrderEdit = 'order:edit';
    case OrderDelete = 'order:delete';


    //========================
    // Discount Management
    //========================

    case DiscountList = 'discount:list';
    case DiscountView = 'discount:view';
    case DiscountCreate = 'discount:create';
    case DiscountEdit = 'discount:edit';
    case DiscountDelete = 'discount:delete';


    //========================
    // Category Management
    //========================

    case CategoryList = 'category:list';
    case CategoryView = 'category:view';
    case CategoryCreate = 'category:create';
    case CategoryEdit = 'category:edit';
    case CategoryDelete = 'category:delete';

}

<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $register = [
        'fullname' => [
            'rules' => 'required|min_length[5]|max_length[50]',
            'errors' => [
                'required' => 'Fullname is required',
                'min_length' => 'Fullname must be at least 5 characters',
                'max_length' => 'Fullname must be at most 50 characters',
            ]
        ],
        'email' => [
            'rules' => 'required|valid_email|is_unique[users.email]',
            'errors' => [
                'required' => 'Email is required',
                'valid_email' => 'Email is invalid',
                'is_unique' => 'Email already exists',
            ]
        ],
        'password' => [
            'rules' => 'required|min_length[8]',
            'errors' => [
                'required' => 'Password is required',
                'min_length' => 'Password must be at least 8 characters',
            ]
        ],
        'confirm_password' => [
            'rules' => 'required|matches[password]',
            'errors' => [
                'required' => 'Confirm Password is required',
                'matches' => 'Confirm Password does not match Password',
            ]
        ],
        'role' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Role is required',
            ]
        ]
    ];

    public $login = [
        'email' => [
            'rules' => 'required|valid_email',
            'errors' => [
                'required' => 'Email is required',
                'valid_email' => 'Email is invalid',
            ]
        ],
        'password' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Password is required',
            ]
        ]
    ];

    public $addProduct = [
        'product_code' => [
            'rules' => 'required|is_unique[products.product_code]',
            'errors' => [
                'required' => 'Product Code is required',
                'is_unique' => 'Product Code already exists',
            ]
        ],
        'product_name' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Product Name is required',
            ]
        ],
        'category' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Category is required',
            ]
        ],
        'price' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Price is required',
                'numeric' => 'Price must be a number',
            ]
        ],
        'stock' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Stock is required',
                'numeric' => 'Stock must be a number',
            ]
        ]
    ];

    public $updateProduct = [
        'id' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Product ID is required',
                'numeric' => 'Product ID must be a number',
            ]
        ]
    ];

    public $checkout = [
        'user_id' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'User ID is required',
            ]
        ],
        'payment_method' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Payment Method is required',
            ]
        ],
        'items' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Items is required',
            ]
        ]
    ];

    public $items = [
        'product_id' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Product ID is required',
            ]
        ],
        'quantity' => [
            'rules' => 'required|numeric|greater_than[0]',
            'errors' => [
                'required' => 'Quantity is required',
                'numeric' => 'Quantity must be a number',
                'greater_than' => 'Please enter a quantity',
            ]
        ]
    ];
}

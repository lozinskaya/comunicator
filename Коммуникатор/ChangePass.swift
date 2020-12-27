//
//  ChangePass.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 27.12.2020.
//

import Foundation
import UIKit

class ChangePass: UIViewController {
    
   
    @IBOutlet weak var oldPassField: UITextField!
    @IBOutlet weak var newPassField: UITextField!
    @IBOutlet weak var reNewPassField: UITextField!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        let paddingView : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        oldPassField.leftView = paddingView
        oldPassField.leftViewMode = .always
        
        let paddingView2 : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        newPassField.leftView = paddingView2
        newPassField.leftViewMode = .always
        
        let paddingView3 : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        reNewPassField.leftView = paddingView3
        reNewPassField.leftViewMode = .always
    }
    
    
    
    override func viewDidAppear(_ animated: Bool) {


    }
    
}

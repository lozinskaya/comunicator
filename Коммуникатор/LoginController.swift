//
//  LoginController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import UIKit

class LoginController: UIViewController {
    
    
    @IBOutlet weak var emailField: UITextField!
    
    @IBOutlet weak var passField: UITextField!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        let paddingView : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        emailField.leftView = paddingView
        emailField.leftViewMode = .always
        
        let paddingView2 : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        passField.leftView = paddingView2
        passField.leftViewMode = .always
        
        self.navigationController?.navigationBar.setValue(true, forKey: "hidesShadow")
        self.navigationItem.backBarButtonItem = UIBarButtonItem(title: "", style: .plain, target: nil, action: nil)
    }
    

    @IBAction func loginClick(_ sender: UIButton) {
        WebFuncs.Login(email: emailField.text ?? "", pass: passField.text ?? "") { result in
            debugPrint(result);
            DispatchQueue.main.async {
                if result == true {
                let storyBoard: UIStoryboard = UIStoryboard(name: "Main", bundle: nil)
                let mainController = storyBoard.instantiateViewController(withIdentifier: "mainController") as! MainController;
                mainController.modalPresentationStyle = .fullScreen;
                        self.present(mainController, animated: true, completion: nil)
                } else {
                let alert = UIAlertController(title: "Ошибка входа", message: "Неверный логин или пароль", preferredStyle: UIAlertController.Style.alert)
                alert.addAction(UIAlertAction(title: "Повторить попытку", style: UIAlertAction.Style.default, handler: nil))
                self.present(alert, animated: true, completion: nil)
                }
            }
    }
    }
}



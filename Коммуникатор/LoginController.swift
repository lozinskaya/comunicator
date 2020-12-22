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



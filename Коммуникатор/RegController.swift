//
//  SettingsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation
import UIKit

class RegController: UIViewController {
    var gender : Settings.Gender = Settings.Gender.no;
    let unselectedColor = UIColor(named: "InputBackColor");
    let selectedColor = UIColor(named: "highlightButton");
    
    @IBOutlet weak var firstnameField: UITextField!
    @IBOutlet weak var lastnameField: UITextField!
    @IBOutlet weak var birthdayField: UITextField!
    
    @IBOutlet weak var menButton: UIButton!
    @IBOutlet weak var womanButton: UIButton!
    @IBOutlet weak var nosexButton: UIButton!
    
    @IBOutlet weak var passField: UITextField!
    @IBOutlet weak var rpassField: UITextField!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
    }
    
    @IBAction func maleClick(_ sender: Any) {
        gender = Settings.Gender.male;
        menButton.backgroundColor = selectedColor;
        womanButton.backgroundColor = unselectedColor;
        nosexButton.backgroundColor = unselectedColor;
    }
    
    @IBAction func femaleClick(_ sender: Any) {
        gender = Settings.Gender.female;
        menButton.backgroundColor = unselectedColor;
        womanButton.backgroundColor = selectedColor;
        nosexButton.backgroundColor = unselectedColor;
    }
    
    @IBAction func nogenderClick(_ sender: Any) {
        gender = Settings.Gender.no;
        menButton.backgroundColor = unselectedColor;
        womanButton.backgroundColor = unselectedColor;
        nosexButton.backgroundColor = selectedColor;
    }
    
    @IBAction func regClick(_ sender: Any) {
        WebFuncs.Reg(params: [
            "firstname": firstnameField.text!,
            "lastname": lastnameField.text!,
            "birthday": birthdayField.text!,
            "gender": gender.rawValue,
            "pass": passField.text!,
            "rpass": rpassField.text!
        ]) { result in
            debugPrint(result);
            DispatchQueue.main.async {
                if result == WebFuncs.Answer.SUCCESS {
                let storyBoard: UIStoryboard = UIStoryboard(name: "Main", bundle: nil)
                let confirmController = storyBoard.instantiateViewController(withIdentifier: "confirmController") as! ConfirmController;
                confirmController.modalPresentationStyle = .fullScreen;
                        self.present(confirmController, animated: true, completion: nil)
                } else {
                let alert = UIAlertController(title: "Ошибка регистрации", message: "Произошла ошибка при регистрации.\nПроверьте данные и повторите попытку.", preferredStyle: UIAlertController.Style.alert)
                alert.addAction(UIAlertAction(title: "Повторить попытку", style: UIAlertAction.Style.default, handler: nil))
                self.present(alert, animated: true, completion: nil)
                }
            }
        }
    }
}

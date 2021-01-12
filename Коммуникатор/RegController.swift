//
//  SettingsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation
import UIKit

class CheckBox: UIButton {
    // Images
    let checkedImage = UIImage(named: "ic_check_box")! as UIImage
    let uncheckedImage = UIImage(named: "ic_check_box_outline_blank")! as UIImage
    
    // Bool property
    var isChecked: Bool = false {
        didSet {
            if isChecked == true {
                self.setBackgroundImage(checkedImage, for: UIControl.State.normal)
            } else {
                self.setBackgroundImage(uncheckedImage, for: UIControl.State.normal)
            }
        }
    }
        
    override func awakeFromNib() {
        self.addTarget(self, action:#selector(buttonClicked(sender:)), for: UIControl.Event.touchUpInside)
        self.isChecked = false
    }
        
    @objc func buttonClicked(sender: UIButton) {
        if sender == self {
            isChecked = !isChecked
        }
    }
}

class RegController: UIViewController {
    var gender : Settings.Gender = Settings.Gender.no;
    let unselectedColor = UIColor(named: "InputBackColor");
    let selectedColor = UIColor(named: "highlightButton");
    
    
    
    @IBOutlet weak var emailField: UITextField!
    @IBOutlet weak var firstnameField: UITextField!
    @IBOutlet weak var lastnameField: UITextField!
    @IBOutlet weak var birthdayField: UITextField!
    
    @IBOutlet weak var menButton: UIButton!
    @IBOutlet weak var womanButton: UIButton!
    @IBOutlet weak var nosexButton: UIButton!
    
    @IBOutlet weak var passField: UITextField!
    @IBOutlet weak var rpassField: UITextField!
    
    @IBOutlet weak var firstnameLabelError: UILabel!
    @IBOutlet weak var lastnameLabelError: UILabel!
    @IBOutlet weak var emailLabelError: UILabel!
    @IBOutlet weak var birthdayLabelError: UILabel!
    @IBOutlet weak var sexLabelError: UILabel!
    @IBOutlet weak var passEmpty: UILabel!
    @IBOutlet weak var passNotEqual: UILabel!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        let paddingView : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        emailField.leftView = paddingView
        emailField.leftViewMode = .always
        
        let paddingView2 : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        firstnameField.leftView = paddingView2
        firstnameField.leftViewMode = .always

        let paddingView3 : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        lastnameField.leftView = paddingView3
        lastnameField.leftViewMode = .always
        
        let paddingView4 : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        birthdayField.leftView = paddingView4
        birthdayField.leftViewMode = .always
        
        let paddingView5 : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        passField.leftView = paddingView5
        passField.leftViewMode = .always
        
        let paddingView6 : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        rpassField.leftView = paddingView6
        rpassField.leftViewMode = .always
        
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
        gender = Settings.Gender.other;
        menButton.backgroundColor = unselectedColor;
        womanButton.backgroundColor = unselectedColor;
        nosexButton.backgroundColor = selectedColor;
    }
    
    @IBAction func regClick(_ sender: Any) {
        WebFuncs.Reg(params: [
            "email": emailField.text!,
            "firstname": firstnameField.text!,
            "surname": lastnameField.text!,
            "birthday": birthdayField.text!,
            "gender": gender.rawValue,
            "pass": passField.text!,
            "r_pass": rpassField.text!
        ]) { result in
            if let result = result {
                debugPrint(result);
                DispatchQueue.main.async {
                    if result["result"] as? String == WebFuncs.Answer.SUCCESS.rawValue {
                        WebFuncs.Login(email: self.emailField.text ?? "", pass: self.passField.text ?? "") { result in
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
                    } else {
                        let alert = UIAlertController(title: "Ошибка регистрации", message: "Произошла ошибка при регистрации.\nПроверьте данные и повторите попытку.", preferredStyle: UIAlertController.Style.alert)
                        alert.addAction(UIAlertAction(title: "Повторить попытку", style: UIAlertAction.Style.default, handler: nil))
                        print(self.gender.rawValue)
                        self.present(alert, animated: true, completion: nil)
                        
                        if self.firstnameField.text == "" {
                            self.firstnameLabelError.isHidden = false
                        }
                        else {
                            self.firstnameLabelError.isHidden = true
                        }
                        
                        if self.lastnameField.text == "" {
                            self.lastnameLabelError.isHidden = false
                        }
                        else {
                            self.lastnameLabelError.isHidden = true
                        }
                        
                        if self.birthdayField.text == "" {
                            self.birthdayLabelError.isHidden = false
                        }
                        else {
                            self.birthdayLabelError.isHidden = true
                        }
                        
                        if self.emailField.text == "" {
                            self.emailLabelError.isHidden = false
                        }
                        else {
                            self.emailLabelError.isHidden = true
                        }
                        
                        if self.passField.text == "" {
                            self.passEmpty.isHidden = false
                        }
                        else {
                            self.passEmpty.isHidden = true
                        }
                        
                        if self.rpassField.text != self.passField.text {
                            self.passNotEqual.isHidden = false
                        }
                        else {
                            self.passNotEqual.isHidden = true
                        }
                        
                        if self.gender.rawValue == "no" {
                            self.sexLabelError.textColor = UIColor.red
                        }
                        else {
                            self.sexLabelError.textColor = UIColor(named: "InputText")
                        }
                    }
                }
            }
        }
    }
}

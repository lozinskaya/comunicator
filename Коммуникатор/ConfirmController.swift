//
//  SettingsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 05.12.2020.
//

import Foundation
import UIKit

class ConfirmController: UIViewController, UITextFieldDelegate {

    
    @IBOutlet weak var otpbox1: UITextField!
    @IBOutlet weak var otpbox2: UITextField!
    @IBOutlet weak var otpbox3: UITextField!
    @IBOutlet weak var otpbox4: UITextField!
    @IBOutlet weak var textUnderCod: UILabel!
    @IBOutlet weak var userEmail: UILabel!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        otpbox1?.delegate = self
        otpbox2?.delegate = self
        otpbox3?.delegate = self
        otpbox4?.delegate = self
    }
    
    func textField(_ textField: UITextField, shouldChangeCharactersIn range:NSRange, replacementString string: String) -> Bool {
            // Range.length == 1 means,clicking backspace
        if (range.length == 0){
            if textField == otpbox1 {
                otpbox2?.becomeFirstResponder()
            }
            if textField == otpbox2 {
                otpbox3?.becomeFirstResponder()
            }
            if textField == otpbox3 {
                otpbox4?.becomeFirstResponder()
            }
            if textField == otpbox4 {
                otpbox4?.resignFirstResponder() /*After the otpbox4 is filled we capture the All the OTP textField and do the server call. If you want to capture the otpbox4 use string.*/
                let otp = "\((otpbox1?.text)!)\((otpbox2?.text)!)\((otpbox3?.text)!)\((otpbox4?.text)!)\(string)"
                WebFuncs.ConfirmReg(params: [
                    "id": Global.user_id,
                    "code": otp
                ]) { result in
                    debugPrint(result)
                }
            }
            textField.text? = string
            return false
        }else if (range.length == 1) {
                if textField == otpbox4 {
                    otpbox3?.becomeFirstResponder()
                }
                if textField == otpbox3 {
                    otpbox2?.becomeFirstResponder()
                }
                if textField == otpbox2 {
                    otpbox1?.becomeFirstResponder()
                }
                if textField == otpbox1 {
                    otpbox1?.resignFirstResponder()
                }
                textField.text? = ""
                return false
        }
        return true
        }

}

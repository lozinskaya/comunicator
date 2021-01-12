//
//  ForgetPassViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 12.01.2021.
//

import UIKit

class ForgetPassViewController: UIViewController {

    @IBOutlet weak var error: UILabel!
    @IBOutlet weak var EmailForgetPass: UITextField!
    @IBAction func SendNewPass(_ sender: Any) {
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()

        let paddingView6 : UIView = UIView(frame: CGRect(x: 0, y: 0, width: 15, height: 20))
        EmailForgetPass.leftView = paddingView6
        EmailForgetPass.leftViewMode = .always
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

}
